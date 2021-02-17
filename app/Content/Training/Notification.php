<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Training;

/**
 * Description of Notification
 *
 * @author eve
 */
class Notification extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name(): string {
        return 'mailer';
    }

    protected function exec() {
        $id = $this->params->get_filtered("id", ["IntMore0", "DefaultNull"]);
        $this->log("id is {$id}");
        if ($id) {
            $training = Training::F($id);
            $this->log(sprintf("trainig is %s", print_r($training, true)));
            if ($training && $training->valid) {
                $this->log("trainig is valid");
                $to = ["eve@ironstar.pw"];
                $user_info = \Auth\UserInfo::F($training->trainer_id);
                if ($user_info && $user_info->valid) {
                    $to[] = $user_info->login;
                }
                $client_info = \Auth\UserInfo::F($training->user_id);
                if ($client_info && $client_info->id) {
                    foreach ($to as $send_to) {
                        $this->log("sending to {$send_to}");
                        if ($send_to) {
                            $this->log(\PresetManager\PresetManager::F()->get_filtered('MAILER_SMTP_PORT',['IntMore0','DefaultNull']));
                            \SWIFT\SWIFTMAILER::F()->send_email_with_template("new_request", $send_to, "Новая запись", [
                                'training' => $training,
                                'client' => $client_info,
                                'trainer' => $user_info,
                                "https" => $this->params->executor_request->https,
                                "host" => $this->params->executor_request->host
                                    ], null, $this);
                        }
                    }
                }
            }
        }
    }

}
