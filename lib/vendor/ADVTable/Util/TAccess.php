<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Util;

trait TAccess {

    public function __get($n) {
        $fn = "__get__{$n}";
        return method_exists($this, $fn) ? $this->$fn() : E::RF("Property `%s` not accessible for read", $n);
    }

    public function __set($n, $v) {
        $fn = "__set__{$n}";
        return method_exists($this, $fn) ? $this->$fn($v) : E::RF("Property `%s` not accessible for write", $n);
    }

}
