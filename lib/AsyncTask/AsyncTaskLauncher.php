<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AsyncTask;

final class AsyncTaskLauncher {

    /** @var AsyncTaskLauncher */
    private static $instance;
    private $log_file_dir;

    private function __construct() {
        static::$instance = $this;
        $this->init();
    }

    public function log(string $message_type, string $message, $log_file_name = "async_task") {
        if (!$this->log_file_dir) {
            $this->log_file_dir = \Config\Config::F()->LOG_DIR;
        }
        $logname = "{$this->log_file_dir}{$log_file_name}.log";
        $file = fopen($logname, "a+b");
        flock($file, LOCK_EX);
        if (filesize($logname) > 1024 * 1024 * 25) {
            ftruncate($file, 0);
        }
        $dt = new \DateTime();
        fwrite($file, sprintf("[%s][%s]:%s\n", $message_type, $dt->format('d.m.Y H:i:s'), $message));
        fflush($file);
        flock($file, LOCK_UN);
        fclose($file);
    }

    public function error_handler($err_no, $err_str, $err_file = null, $err_line = null, $err_context = null): bool {
        $this->log("error", "{$err_str} in `{$err_file}` as `{$err_line}`");
        return false;
    }

    public function exception_handler(\Throwable $e) {
        $this->log("exception", "{$e->getMessage()} in `{$e->getFile()}` at {$e->getLine()}");
        die();
    }

    private function init() {
        set_error_handler([$this, 'error_handler'], E_ALL);
        set_exception_handler([$this, 'exception_handler']);
    }

    public static function F(): AsyncTaskLauncher {
        return static::$instance ? static::$instance : new static();
    }

    public function execute() {
        $server = isset($_SERVER) && is_array($_SERVER) ? $_SERVER : [];
        $arg_count = array_key_exists('argc', $server) ? intval($server['argc']) : 0;
        if ($arg_count === 3) {
            $args = array_key_exists('argv', $server) && is_array($server['argv']) ? $server['argv'] : [];
            if (is_array($args) && count($args) === $arg_count) {
                $bootstrap_path = base64_decode($args[1]);
                if (file_exists($bootstrap_path)) {
                    require_once $bootstrap_path;
                    if (class_exists("\a35115dc61264b38be64e25bb0aeb65e\Bootstrap", false)) {
                        \a35115dc61264b38be64e25bb0aeb65e\Bootstrap::F();
                        $params = unserialize(base64_decode($args[2]));
                        if ($params && is_object($params) && ($params instanceof AsyncTaskParams)) {
                            $params->execute($this);
                        }
                    }
                }
            }
        }
    }

}

AsyncTaskLauncher::F()->execute();
