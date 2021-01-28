<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AsyncTask;

/**
 * @property string $bootstrap_path
 * @property string $config_php_path
 * @property string $task_class
 * @property \Router\Request $executor_request
 * @property string $launcher_path
 * @property string $encoded_bootstrap
 * @property string $encoded_self
 * @property bool $debug_out
 */
class AsyncTaskParams implements \DataMap\IDataMap {

    use \common_accessors\TCommonAccess,
        \DataMap\TInternalDataMapProxy;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var \DataMap\IDataMap */
    protected $params;

    /** @var string */
    protected $config_php_path;

    /** @var string */
    protected $task_class;

    /** @var string */
    protected $bootstrap_path;

    /** @var \Router\Request */
    protected $executor_request;

    /** @var string */
    protected $launcher_path;

    /** @var bool */
    protected $debug_out = false;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    protected function __get__bootstrap_path() {
        return $this->bootstrap_path;
    }

    protected function __get__config_php_path() {
        return $this->config_php_path;
    }

    protected function __get__task_class() {
        return $this->task_class;
    }

    protected function __get__executor_request() {
        return $this->executor_request;
    }

    protected function __get__launcher_path() {
        return $this->launcher_path;
    }

    protected function __get__encoded_bootstrap() {
        return base64_encode($this->bootstrap_path);
    }

    protected function __get__encoded_self() {
        return base64_encode(serialize($this));
    }

    protected function __get__debug_out() {
        return $this->debug_out;
    }

    //</editor-fold>

    public function set_debug_out(bool $x = false): AsyncTaskParams {
        $this->debug_out = $x;
        return $this;
    }

    protected function __construct(string $task_class) {
        $this->bootstrap_path = \a35115dc61264b38be64e25bb0aeb65e\Bootstrap::F()->get_bootstrap_path();
        $this->executor_request = \Router\Request::F();
        $this->task_class = $task_class;
        if (!\Helpers\Helpers::class_inherits($this->task_class, AsyncTaskAbstract::class)) {
            AsyncTaskError::RF("executor class `%s` not found or does not inherits AsynTask", $task_class);
        }
        $t = [];
        $this->params = \DataMap\CommonDataMap::F()->rebind($t);
        $this->config_php_path = \Helpers\Helpers::NEString(\Config\Config::F()->PHP_EXECUTOR_PATH, "php");
        $this->launcher_path = __DIR__ . DIRECTORY_SEPARATOR . "AsyncTaskLauncher.php";
    }

    public function add(string $key, $value): AsyncTaskParams {
        $this->params->set($key, $value);
        return $this;
    }

    public function add_array(array $kv): AsyncTaskParams {
        foreach ($kv as $k => $v) {
            $this->params->set($k, $v);
        }
        return $this;
    }

    /**
     * run task in separate process
     * @param array $args
     */
    public function run(array $args = null) {
        $args ? $this->add_array($args) : 0;
        $debug_file_name = $this->debug_out ? (__DIR__ . DIRECTORY_SEPARATOR . "output.nohup") : "/dev/null";
        $rl = [];
        if ($this->debug_out) {
            file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "cmd_line.tx", "nohup setsid {$this->config_php_path} {$this->launcher_path} {$this->__get__encoded_bootstrap()} {$this->__get__encoded_self()} > {$debug_file_name} 2>&1 &");
        }
        exec("nohup setsid {$this->config_php_path} {$this->launcher_path} {$this->__get__encoded_bootstrap()} {$this->__get__encoded_self()} > {$debug_file_name} 2>&1 &", $rl);
        if ($this->debug_out) {
            file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "rlout.nh", print_r($rl));
        }
    }

    /**
     * run task in current process
     */
    public function execute(AsyncTaskLauncher $launcher = null) {
        if (class_exists($this->task_class) && \Helpers\Helpers::class_inherits($this->task_class, AsyncTaskAbstract::class)) {
            $class = $this->task_class;
            $task = $class::F($this, $launcher);
            /* @var $task AsyncTaskAbstract */
            $task->execute();
        } else {
            AsyncTaskError::RF("no class `%s` found or class is not executor", $this->task_class);
        }
    }

    protected function t_array_data_map_get_internal_map(): \DataMap\IDataMap {
        return $this->params;
    }

    public static function F(string $task_class): AsyncTaskParams {
        return new static($task_class);
    }

}
