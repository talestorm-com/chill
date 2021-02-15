<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common_accessors;

class TCAReadError extends \Errors\common_error {

    const MSG_TEMPLATE = "cant access property `%s` in class `%s` for read. no getter method";

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
