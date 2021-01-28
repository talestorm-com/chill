<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd\Helpers;

/**
 * Description of PayportCreateLink
 *
 * @author eve
 */
class PayportCreateLink {

    //put your code here

    public static function run() {
        $user_id = \Auth\Auth::F()->get_id();
        $token = \DataMap\InputDataMap::F()->get_filtered('token',['Strip','Trim','NEString','DefaultNull']);
        \Helpers\Helpers::csrf_check_throw('wallet',$token,false);
        $summ = \DataMap\InputDataMap::F()->get_filtered("summ_amount", ["Float", "DefaultNull"]);        
        $summ && $summ > 0 ? 0 : $summ = 100.0;
        $builder = \DB\SQLTools\SQLBuilder::F();
        $a = "@a" . md5(implode("A", [__METHOD__, $user_id]));
        $builder->push("INSERT INTO chill__orders(user_id,amount,status,created,updated) VALUES(:P,:S,'created',NOW(),NOW());SET {$a}=LAST_INSERT_ID();")
                ->push_params([":P" => $user_id, ":S" => $summ]);
        $order_id = $builder->execute_transact($a);
        $payport_url = \PresetManager\PresetManager::F()->get_filtered("PAYPORT_URL", ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']);
        $payment_url = "{$payport_url}/api/v2/payment";

        $payment_data = [
            "outlet_id" => \PresetManager\PresetManager::F()->get_filtered("PAYPORT_OUTLET", ["IntMore0", 'Default0']),
            "amount" => intval($summ * 100),
            "currency_code" => "RUR",
            "order_number" => "{$order_id}",
            "payment_desc" => "Пополнение счета в онлайн-кинотеатре Chill",
        ];

        $token = \PresetManager\PresetManager::F()->get_filtered("PAYPORT_TOKEN", ["Strip", "Trim", 'NEString', 'DefaultEmptyString']);
        $encoded_data = json_encode($payment_data);

        $curl = curl_init($payment_url);
        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $encoded_data,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json; charset=utf-8",
                "Content-Length: " . strlen($encoded_data),
                "Authorization: Bearer {$token}",
            ]
        ]);
        $result = curl_exec($curl);
        $result_json = json_decode($result, true);
        $result_clean = \Filters\FilterManager::F()->apply_filter_array(is_array($result_json) ? $result_json : [], [
            "id" => ['IntMore0'],
            "status" => ["Strip", 'Trim', 'NEString'], //"FILLED",
            "amount" => ['IntMore0'],
            "currency_code" => ['Strip', 'Trim', 'NEString'],
            "order_number" => ['Strip', 'Trim', 'NEString'],
            "pay_url" => ['Strip', 'Trim', 'NEString'], //"https:\/\/paytest.payport.pro\/40c148e9d4c43bb29e5fb5e4752e8157" 
        ]);
        \Filters\FilterManager::F()->raise_array_error($result_clean);
        \DB\SQLTools\SQLBuilder::F()->push("UPDATE chill__orders SET payport_id=:P,updated=NOW(),status='FILLED' WHERE id=:PP;")
                ->push_params([
                    ":P" => $result_clean["id"],
                    ":PP" => $order_id])
                ->execute_transact();
        return $result_clean['pay_url'];
    }

}
