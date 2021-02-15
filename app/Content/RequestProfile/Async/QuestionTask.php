<?php

namespace Content\RequestProfile\Async;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewRequestTask
 *
 * @author eve
 */
class QuestionTask extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name(): string {
        return 'mailer';
    }

    protected function exec() {
        $row = $this->params->get_filtered("data", ["NEArray", "DefaultEmptyArray"]);
        $data_raw = \Filters\FilterManager::F()->apply_filter_array($row, [
            'name' => ["Strip", "Trim", "NEString"],
            "email" => ["EmailMatch"],
            "message" => ["Strip", "Trim", "NEString"],
            "ppa" => ["Boolean", "DefaultTrue"],
        ]);
        \Filters\FilterManager::F()->raise_array_error($data_raw);

        $this->notify_admin($data_raw);
    }

    protected function notify_admin(array $data_row) {
        $to = ["testaccount@ironstar.pw"];
        $to[] = \PresetManager\PresetManager::F()->get_filtered("NOTIFICATION_EMAIL", ["EmailMatch", "DefaultNull"]);
        foreach ($to as $send_to) {
            $this->log("sending to {$send_to}");
            if ($send_to) {
                $this->log(\PresetManager\PresetManager::F()->get_filtered('MAILER_SMTP_PORT', ['IntMore0', 'DefaultNull']));
                \SWIFT\SWIFTMAILER::F()->send_email_with_template("new_question", $send_to, "Новый вопрос", [
                    'request' => $data_row,
                    "https" => $this->params->executor_request->https,
                    "host" => $this->params->executor_request->host
                        ], null, $this);
            }
        }
    }

}
