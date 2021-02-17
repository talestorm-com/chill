<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

class EmptyValue extends Value {

    protected $message = "value_is_empty";

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public static function is($x): bool {
        return $x && is_object($x) && ($x instanceof EmptyValue);
    }

}
