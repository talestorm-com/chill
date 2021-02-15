<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Order\Tasks;

class PreorderNotifyTask extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name() {
        return "mailer";
    }

    protected function exec() {
        $order_id = $this->params->get_filtered("order_id", ["IntMore0", "DefaultNull"]);
        $order = \Content\Order\Order::F($order_id);
        if ($order && $order->valid) {
            $to = [];            
            $to[]="megafrog@yandex.ru";
            $to[]="eve@ironstar.pw";
            $to[] = \PresetManager\PresetManager::F()->get_filtered("MAILER_DEFAULT_TO", ["Trim", "NEString", "EmailMatch", "DefaultNull"]);            
            foreach ($to as $send_to) {
                if ($send_to) {
                    \SWIFT\SWIFTMAILER::F()->send_email_with_template("new_preorder", $send_to, "Новый предзаказ", ['order' => $order],null,$this);
                }
            }
        }
    }

}
