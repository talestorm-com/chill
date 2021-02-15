<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AsyncTask;

/**
 * @property AsyncTaskParams $params
 * @property AsyncTaskLauncher $launcher
 */
abstract class AsyncTaskAbstract {

    use \common_accessors\TCommonAccess;

    /** @var AsyncTaskParams */
    protected $params;

    /** @var AsyncTaskLauncher */
    protected $launcher;

    protected function __get__params() {
        return $this->params;
    }

    protected function __get__launcher() {
        return $this->launcher;
    }

    protected final function __construct(AsyncTaskParams $params, AsyncTaskLauncher $launcher = null) {
        $this->params = $params;
        $this->launcher = $launcher;
        $this->on_init();
    }

    protected function get_log_file_name() {
        return "async_task";
    }

    public function log(string $message, string $message_type = 'info') {
        $this->launcher ? $this->launcher->log($message_type, $message, $this->get_log_file_name()) : 0;
        return $this;
    }

    /**
     * @override
     */
    protected function on_init() {
        
    }

    protected function on_before_execute() {
        
    }

    protected function on_after_execute() {
        
    }

    protected abstract function exec();

    public final function execute() {
        $this->on_before_execute();
        $this->exec();
        $this->on_after_execute();
    }

    public static final function F(AsyncTaskParams $params, AsyncTaskLauncher $launcher = null): AsyncTaskAbstract {
        return new static($params, $launcher);
    }

    /**
     * 
     * @return \AsyncTask\AsyncTaskParams
     */
    public static final function mk_params(): AsyncTaskParams {
        return AsyncTaskParams::F(get_called_class());
    }

}
