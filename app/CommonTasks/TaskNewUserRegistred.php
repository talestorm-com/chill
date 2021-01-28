<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommonTasks;

class TaskNewUserRegistred extends \AsyncTask\AsyncTaskAbstract {

    protected function exec() {
        $user_id = $this->params->get_filtered("user_id", ["IntMore0", "DefaultNull"]);
        if ($user_id) {
            $user_info = \Auth\UserInfo::F($user_id);
            if ($user_info && $user_info->valid) {
                \SWIFT\SWIFTMAILER::F()->send_email_with_template("new_user", $user_info->login, "Приветствуем на сайте LARRO", [
                    'user_info' => $user_info,
                    "https" => $this->params->executor_request->https,
                    "host" => $this->params->executor_request->host
                        ], [], $this);
            }
        }
    }

}
