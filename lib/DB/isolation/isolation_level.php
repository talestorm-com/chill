<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DB\isolation;

/**
 * @property string $value
 */
abstract class isolation_level implements IIsolationLevel {

    use \common_accessors\TCommonAccess;

    const level = '';

    protected final function __get__value() {
        return static::level;
    }

    public final function get_value(): string {
        return static::level;
    }

    private final function __construct() {
        
    }

    /**
     * 
     * @return \static
     */
    public final static function F() {
        return new static();
    }

}
