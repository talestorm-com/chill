<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ns23b41a9e233048dc802a9cc2982f792b;

require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "www" . DIRECTORY_SEPARATOR . "__bootstrap.php";

class Backup {

    protected $config_name = null;

    protected function __construct() {
        $args = isset($_SERVER) && is_array($_SERVER) && array_key_exists("argv", $_SERVER) && is_array($_SERVER["argv"]) ? array_slice($_SERVER["argv"], 1, 100) : [];
        if (count($args)) {
            $this->config_name = \Helpers\Helpers::NEString($args[0], null);
        }
        if (!$this->config_name) {
            echo "usage: php Backup.php <config_name>";
            die();
        }
    }

    public function run() {
        \Backup\AbstractBackupTask::mk_params()->add("config", $this->config_name)->run();
    }

    /**
     * 
     * @return \ns23b41a9e233048dc802a9cc2982f792b\Backup
     */
    public static function F(): Backup {
        return new static();
    }

}

Backup::F()->run();
