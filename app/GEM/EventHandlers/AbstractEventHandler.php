<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GEM\EventHandlers;

abstract class AbstractEventHandler {

    public abstract function run(\GEM\EventKVS $params = null);

    public static final function srun(\GEM\EventKVS $params = null) {
        static::F()->run($params);
    }

    protected static abstract function get_message();

    public static final function register() {
        \GEM\GEM::F()->on(static::get_message(), get_called_class(), "srun");
    }

    protected static final function F(): AbstractEventHandler {
        return new static();
    }

    protected final function __construct() {
        $this->on_init();
    }

    protected function on_init() {
        
    }

}
