<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\RequestProfile\Async;

/**
 * Description of OnRequestDeleted
 *
 * @author eve
 */
class OnRequestDeleted extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name(): string {
        return 'mailer';
    }

    protected function exec() {
        $request = $this->params->get("request_info");
        if ($request) {
            $user_info = \Auth\UserInfo::F(intval($request["user_id"]));
            if ($user_info) {               
                $this->notify_admin($user_info, $request);
            }
        }
    }    

    protected function notify_admin(\Auth\UserInfo $user_info, array $request_info) {        
        if ($user_info && $user_info->valid) {
            $this->log("user_is_valid");
            $to = ["testaccount@ironstar.pw"];
            $to[] = \PresetManager\PresetManager::F()->get_filtered("NOTIFICATION_EMAIL", ["EmailMatch", "DefaultNull"]);
            foreach ($to as $send_to) {
                $this->log("sending to {$send_to}");
                if ($send_to) {
                    $this->log(\PresetManager\PresetManager::F()->get_filtered('MAILER_SMTP_PORT', ['IntMore0', 'DefaultNull']));
                    \SWIFT\SWIFTMAILER::F()->send_email_with_template("request_canceled", $send_to, "Заявка отменена", [
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
