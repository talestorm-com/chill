<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PayPort;

/**
 * Description of PayportValidator
 *
 * @author eve
 */
class PayportValidator extends \AsyncTask\AsyncTaskAbstract {

    protected $payport_url;
    
    protected function get_log_file_name(): string {
        return 'payport';
    }

    protected function exec() {
        $this->payport_url =  \PresetManager\PresetManager::F()->get_filtered("PAYPORT_URL",['Strip','Trim','NEString','DefaultEmptyString']);
        $order_id = $this->params->get_filtered("payport_id", ['IntMore0', 'DefaultNull']);
        if ($order_id) {
            $this->run_checking($order_id);
        }
    }

    protected function get_payment_data_local(int $order_id) {
        return \DB\DB::F()->queryRow("SELECT * FROM chill__orders WHERE payport_id=:P", [":P" => $order_id]);
    }

    protected function get_payment_data_remote(int $order_id) {
        $url = "{$this->payport_url}/api/v2/payment/{$order_id}";
        $token = \PresetManager\PresetManager::F()->get_filtered("PAYPORT_TOKEN", ["Strip", "Trim", 'NEString', 'DefaultEmptyString']);
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_HTTPGET => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$token}",
            ]
        ]);
        $data = curl_exec($curl);
        $this->log($data,'order data recieved');
        return json_decode($data, true);
    }

    protected function run_checking(int $order_id) {
        $payment = $this->get_payment_data_local($order_id);
        if (!$payment) {
            $this->log("payment not found {$order_id}");
            return;
        }
        $c = 0;
        while ($c < 120) {
            $c++;
            $payment = $this->get_payment_data_local($order_id);
            if (mb_strtoupper($payment['status'],'UTF-8') !== 'FILLED') {
                $this->log("payment status wrong {$order_id}:{$payment['status']}");
                return;
            }
            $d = $this->get_payment_data_remote($order_id);
            if ($d && is_array($d)) {
                try {
                    $dc = \Filters\FilterManager::F()->apply_filter_array($d, [
                        "id" => ['IntMore0'],
                        "status" => ['Strip', 'Trim', 'NEString','Uppercase'], //"EXECUTED",
                        "amount" => ['IntMore0'], //"10000",
                        "currency_code" => ['Strip', 'Trim', 'NEString'], //"RUR",
                        "order_number" => ['IntMore0','DefaultNull'],
                    ]);
                    
                    if (\Filters\FilterManager::F()->is_values_ok($dc)) {
                        if ($dc['status'] === 'EXECUTED') {
                            return $this->success_payment($dc);
                        } else if ($dc['status'] === 'REJECTED') {
                            return $this->fail_payment($dc);
                        }
                    }
                } catch (\Throwable $e) {
                    $this->log(sprintf("error %s in %s at %s", $e->getMessage(), $e->getFile(), $e->getLine()), 'error');
                    $this->log($e->getTraceAsString(), 'trace');
                }
            }
            sleep(30);
        }
    }

    protected function success_payment(array $payment_data) {
        $local_data = $this->get_payment_data_local($payment_data['id']);
        if ($local_data && $local_data['status'] === 'FILLED') {
            $query = "INSERT INTO user__wallet(id,money) VALUES(:Pu,0) ON DUPLICATE KEY UPDATE money=money+VALUES(money);
                 UPDATE user__wallet A JOIN chill__orders B ON(A.id=B.user_id)
                 SET B.status='EXECUTED',
                     A.money=A.money+(:Pm)
                 WHERE A.id=:Pu AND B.payport_id=:Po AND B.status='FILLED';    
                    ";
            \DB\SQLTools\SQLBuilder::F()->push($query)->push_params([
                ":Pu" => $local_data["user_id"],
                ":Pm" => $payment_data['amount'] / 100,
                ":Po" => $payment_data["id"],
            ])->execute_transact();
        }
    }

    protected function fail_payment(array $payment_data) {
        $local_data = $this->get_payment_data_local($payment_data['id']);
        if ($local_data && $local_data['status'] === 'FILLED') {
            $query = "
                 UPDATE chill__orders 
                 SET status='REJECTED'                     
                 WHERE payport_id=:Po AND status='FILLED';    
                    ";
            \DB\SQLTools\SQLBuilder::F()->push($query)->push_params([
                ":Po" => $payment_data["id"],
            ])->execute_transact();
        }
    }

}
