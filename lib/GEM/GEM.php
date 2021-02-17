<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GEM;

class GEM implements IGEMConsts {

    use \common_accessors\TCommonAccess;

    protected static $use_props_export = true;

    /** @var GEM */
    protected static $instance = null;

    /** @var EventCollection[] */
    protected $events = null;

    /** @var bool */
    protected $changed = false;

    protected function __construct() {
        static::$instance = $this;
        $this->events = [];
        $this->reload();
        $this->changed = false;
    }

    protected function reload() {
        $path = \Config\Config::F()->EVENT_REGISTRY . "events.bin";
        if (file_exists($path)) {
            $this->events = unserialize(file_get_contents($path));
        }
        return $this;
    }

    protected function save() {
        if ($this->changed) {
            $path = \Config\Config::F()->EVENT_REGISTRY . "events.bin";
            file_put_contents($path, serialize($this->events), LOCK_EX);
            $this->changed = false;
        }
        return $this;
    }

    public function __destruct() {
        $this->save();
        if (static::$use_props_export) {
            $this->export_names();
        }
    }

    public function run(string $event_name, EventKVS $event_params = null) {
        $event_name = mb_strtoupper($event_name, "UTF-8");
        if (array_key_exists($event_name, $this->events)) {
            $this->events[$event_name]->run($event_params);
        }
    }

    public function on(string $event_name, string $class, string $method): GEM {
        $event_name = mb_strtoupper($event_name, "UTF-8");
        if (!$this->exists($event_name, $class, $method)) {
            if (!array_key_exists($event_name, $this->events)) {
                $this->events[$event_name] = EventCollection::F($event_name);
            }
            $this->events[$event_name]->add($class, $method);
            $this->changed = ($this->changed || $this->events[$event_name]->changed);
        }
        return $this;
    }

    public function exists(string $event_name, string $class, string $method): bool {
        $event_name = mb_strtoupper($event_name, "UTF-8");
        if (array_key_exists($event_name, $this->events)) {
            return $this->events[$event_name]->exists($class, $method);
        }
        return false;
    }

    /**
     * 
     * @return \GEM\GEM
     */
    public static function F(): GEM {
        return static::$instance ? static::$instance : new static();
    }

    public function export_names() {
        $m = array_keys($this->events);
        $text = "<?php \n\n namespace GEM;\n\ninterface IGEMConsts{\n\n";
        foreach ($m as $name) {
            $text .= "const {$name} = '{$name}';\n";
        }
        $text .= "}\n";
        $path = rtrim(__DIR__, "\\/") . DIRECTORY_SEPARATOR . "IGEMConsts.php";
        file_put_contents($path, $text, LOCK_EX);
    }

}
