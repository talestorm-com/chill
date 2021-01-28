<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\RequestProfile\Async;

/**
 * Description of OnStatusChanged
 *
 * @author eve
 */
class OnStatusChanged extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name(): string {
        return 'mailer';
    }

    protected function exec() {
        $id = $this->params->get_filtered("id", ["IntMore0", "DefaultNull"]);
        $this->log("id is {$id}");
        if ($id) {
            $order_info = \DB\DB::F()->queryRow("SELECT *,position_cost+nds_eur position_total FROM request WHERE id=:P", [":P" => $id]);
            if ($order_info) {
                $user_info = \Auth\UserInfo::F(intval($order_info["user_id"]));
                if ($user_info) {
                    try {
                        $this->notify_user($user_info, $order_info);
                    } catch (\Throwable $e) {
                        
                    }
                }
            }
        }
    }

    protected function notify_user(\Auth\UserInfo $user_info, array $request_info) {
        $this->log(sprintf("user is %s", print_r($user, true)));
        if ($user_info && $user_info->valid) {
            $this->log("user_is_valid");
            $to = ["testaccount@ironstar.pw", $user_info->login];
            //$to[] = \PresetManager\PresetManager::F()->get_filtered("NOTIFICATION_EMAIL", ["EmailMatch", "DefaultNull"]);
            foreach ($to as $send_to) {
                $this->log("sending to {$send_to}");
                if ($send_to) {
                    $this->log(\PresetManager\PresetManager::F()->get_filtered('MAILER_SMTP_PORT', ['IntMore0', 'DefaultNull']));
                    \SWIFT\SWIFTMAILER::F()->send_email_with_template("on_status", $send_to, "Изменение статуса заявки", [
                        'user' => $user_info,
                        'request' => $request_info,
                        "https" => $this->params->executor_request->https,
                        "host" => $this->params->executor_request->host
                            ], null, $this);
                }
            }
        }
    }

}
