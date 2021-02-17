<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommonTasks;

/**
 * Description of TaskNewOrderCreated
 *
 * @author studio2
 */
class TaskNewOrderCreated extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name() {
        return "new_order_mail";
    }

    protected function exec() {
        $order_id = $this->params->get_filtered("order_id", ["IntMore0", "DefaultNull"]);
        $new_user = $this->params->get_filtered("new_user", ["Boolean", "DefaultFalse"]);
        $new_password = null;
        $new_user_id = null;
        if ($new_user) {
            $new_user_id = $this->params->get_filtered("new_user_id", ["IntMore0", "DefaultNull"]);
            $new_password = $this->params->get_filtered("new_user_password", ["NEString", "DefaultNull"]);
        }
        try {
            $this->notify_order($order_id);
        } catch (\Throwable $ee) {
            $this->log($ee->getMessage(), "error");
        }
        if ($new_user && $new_user_id && $new_password) {
            try {
                $this->notify_cabinet($new_user_id, $new_password);
            } catch (\Throwable $ee) {
                $this->log($ee->getMessage(), "error");
            }
        }
    }

    protected function notify_order($order_id) {
        $order = \Content\Order\Order::F($order_id);
        if ($order && $order->valid) {
            try {
                $this->notify_admin($order);
            } catch (\Throwable $ee) {
                $this->log($ee->getMessage(), "error");
            }
            try {
                $this->notify_client($order);
            } catch (\Throwable $ee) {
                $this->log($ee->getMessage(), "error");
            }
        }
    }

    protected function notify_admin(\Content\Order\Order $order) {
        $to[] = "megafrog@yandex.ru";
        $to[] = "eve@ironstar.pw";
        $to[] = \PresetManager\PresetManager::F()->get_filtered("MAILER_DEFAULT_TO", ["Trim", "NEString", "EmailMatch", "DefaultNull"]);
        foreach ($to as $send_to) {
            if ($send_to) {
                \SWIFT\SWIFTMAILER::F()->send_email_with_template("order/admin", $send_to, "Новый заказ № {$order->id}", [
                    "order" => $order,
                    'https' => $this->params->executor_request->https,
                    'host' => $this->params->executor_request->host,
                        ], null, $this);
            }
        }
    }

    protected function notify_client(\Content\Order\Order $order) {
        $to = \Filters\FilterManager::F()->apply_chain($order->user_email, ["NEString", "EmailMatch", "DefaultNull"]);
        if ($to) {
            \SWIFT\SWIFTMAILER::F()->send_email_with_template("order/client", $to, "Новый заказ на сайте LARRO", [
                "order" => $order,
                'https' => $this->params->executor_request->https,
                'host' => $this->params->executor_request->host,
                    ], null, $this);
        }
    }

    protected function notify_cabinet($new_user_id, $new_password) {
        $user_info = \Auth\UserInfo::F($new_user_id);
        if ($user_info && $user_info->valid) {
            \SWIFT\SWIFTMAILER::F()->send_email_with_template("order/cabinet", $user_info->login, "Приветствуем на сайте LARRO", [
                'user_info' => $user_info,
                'password' => $new_password,
                'https' => $this->params->executor_request->https,
                'host' => $this->params->executor_request->host,
                    ], null, $this);
        }
    }

}
