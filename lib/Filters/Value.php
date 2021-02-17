<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

/**
 * @property string $message
 */
abstract class Value {

    use \common_accessors\TCommonAccess;

    protected $message;

    protected function __get__message() {
        return $this->message;
    }

    public static function is($x): bool {
        return $x && is_object($x) && ($x instanceof Value);
    }

}
