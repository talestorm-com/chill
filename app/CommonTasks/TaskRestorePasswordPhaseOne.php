<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommonTasks;

/**
 * Description of TaskRestorePasswordPhaseOne
 *
 * @author studio2
 */
class TaskRestorePasswordPhaseOne extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name() {
        return "restore_mailer";
    }

    protected function exec() {
        try{
        $user_login = $this->params->get_filtered("user_login", ["Strip", "Trim", "NEString", "EmailMatch", "DefaultNull"]);
        if ($user_login) {
            $user_info = \Auth\UserInfo::S($user_login);
            if ($user_info && $user_info->valid) {
                \SWIFT\SWIFTMAILER::F()->send_email_with_template("restore/phase1", $user_info->login, "Восстановление пароля", [
                    'user_info' => $user_info,
                    'https' => $this->params->executor_request->https,
                    'host' => $this->params->executor_request->host
                        ], NULL, $this);
            } else {
                $this->log("restore on non existsng user", "error");
                $this->log(sprintf("non_existsing_user `%s`", $user_login), "error");
            }
        } else {
            $this->log("invalid user login", "error");
        }
        } catch (\Throwable $ee){
            $this->log($ee->getMessage(), "error");            
        }
    }

}
