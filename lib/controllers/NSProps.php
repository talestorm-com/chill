<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers;

/**
 * класс дефолтов для неймспейса
 * @property string $default_controller
 * @property string $default_action
 */
class NSProps implements INSProps {

    use \common_accessors\TCommonAccess;

    protected final function __get__default_controller() {
        return $this->get_default_controller();
    }

    protected final function __get__default_action() {
        return $this->get_default_action();
    }

    protected final function __construct() {
        
    }

    public function get_default_controller(): string {
        return 'Index';
    }

    public function get_default_action(): string {
        return 'index';
    }

    /**
     * 
     * @return \static
     */
    public static final function F(): INSProps {
        return new static();
    }

}
