<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\RequestProfile\Async;

/**
 * Description of NewUserTask
 *
 * @author eve
 */
class NewUserTask extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name(): string {
        return 'mailer';
    }

    protected function exec() {
        $id = $this->params->get_filtered("id", ["IntMore0", "DefaultNull"]);
        $this->log("id is {$id}");
        $this->log("new_user_task");
        if ($id) {
            $user = \Auth\UserInfo::F($id);
            $this->log(sprintf("user is %s", print_r($user, true)));
            if ($user && $user->valid) {
                $this->log("user_is_valid");
                $to = ["testaccount@ironstar.pw"];
                $to[] = \PresetManager\PresetManager::F()->get_filtered("NOTIFICATION_EMAIL", ["EmailMatch", "DefaultNull"]);
                foreach ($to as $send_to) {
                    $this->log("sending to {$send_to}");
                    if ($send_to) {
                        $this->log(\PresetManager\PresetManager::F()->get_filtered('MAILER_SMTP_PORT', ['IntMore0', 'DefaultNull']));
                        \SWIFT\SWIFTMAILER::F()->send_email_with_template("new_user", $send_to, "Новый пользователь", [
                            'user' => $user,
                            "https" => $this->params->executor_request->https,
                            "host" => $this->params->executor_request->host
                                ], null, $this);
                    }
                }
            }
        }
    }

   

}
