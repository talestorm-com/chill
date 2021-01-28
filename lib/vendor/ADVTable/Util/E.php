<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Util;

class E extends \Exception {

    /**
     * 
     * @param String $message
     * @throws DataError
     */
    public static function R($message) {
        throw new static($message);
    }

    /**
     * 
     * @param string $message
     * @param mixed $_ [optional] 
     */
    public static function RF($message) {
        static::R(call_user_func_array('sprintf', func_get_args()));
    }

}
