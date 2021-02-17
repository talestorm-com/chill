<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common_accessors;

trait TCommonAccess {

    public function __get($name) {
        $fn = $this->t_common_access_get_getter_for($name);
        return method_exists($this, $fn) ? $this->$fn() : TCAReadError::F($name, __CLASS__);
    }

    protected function t_common_access_get_getter_for(string $name): string {
        return "__get__{$name}";
    }

    public function __set($name, $value) {
        $fn = $this->t_common_access_get_setter_for($name);
        return method_exists($this, $fn) ? $this->$fn($value) : TCAWriteError::F($name, __CLASS__);
    }

    protected function t_common_access_get_setter_for(string $name): string {
        return "__set__{$name}";
    }

}
