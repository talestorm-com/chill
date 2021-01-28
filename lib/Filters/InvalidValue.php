<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

class InvalidValue extends Value {

    public function __construct(string $message) {
        $this->message = $message;
    }

    /**
     * 
     * @param string $message
     * @return \static
     */
    public static function F(string $message) {
        return new static($message);
    }

    public static function is($x): bool {
        return $x && is_object($x) && ($x instanceof InvalidValue);
    }

}
