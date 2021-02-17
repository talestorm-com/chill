<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ChillFosProcessor;

/**
 * Description of Task
 *
 * @author eve
 */
class Task extends \AsyncTask\AsyncTaskAbstract {

    protected function exec() {
        $id = $this->params->get_filtered('id', ['IntMore0', 'DefaultNull']);
        if ($id) {
            $this->run_task($id);
        }
    }

    protected function run_task(int $id) {
        $row = \DB\DB::F()->queryRow("SELECT * FROM media_new_request WHERE id=:P", [":P" => $id]);
        if ($row) {
            $this->send($row);
        }
    }

    protected function send(array $row) {
        $mailto = \PresetManager\PresetManager::F()->get_filtered('MAIL_ABOUT_REQUEST', ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull']);
        $mailto = $mailto ? $mailto : 'megafrog@yandex.ru';
        \SWIFT\SWIFTMAILER::F()->send_email_with_template('new_request_fos', $mailto,
                "Новый контент!", $row, null, $this);
    }

}
