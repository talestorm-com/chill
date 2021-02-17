<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common_accessors;

class TCAWriteError extends \Errors\common_error {

    const MSG_TEMPLATE = "cant access property `%s` in class `%s` for write. no setter method";

    /**
     * 
     * @param string $prop
     * @param string $class
     * @throws \static
     */
    public static function F(string $prop, string $class) {
        static::RF(static::MSG_TEMPLATE, $prop, $class);
    }

}
