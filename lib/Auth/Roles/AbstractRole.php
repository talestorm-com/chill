<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth\Roles;

abstract class AbstractRole implements IRole {

    protected final function __construct() {
        
    }

    public static function is($x): bool {
        $m = static::class;
        return $x && is_object($x) && ($x instanceof $m);
    }

    /**
     * 
     * @return \Auth\IRole
     */
    public static function F(): IRole {
        return new static();
    }

    public static function FT(string $cn): IRole {
        $rt = ucfirst(mb_strtolower($cn, 'UTF-8'));
        $class_name = "\\" . trim(__NAMESPACE__, "\\/") . "\\Role{$rt}";
        if (class_exists($class_name) && \Helpers\Helpers::class_implements($class_name, IRole::class)) {
            return $class_name::F();
        }
        return RoleNone::F();
    }

    public function is_a(string $required_class): bool {  
        
        $result =  \Helpers\Helpers::class_inherits(static::class, $required_class) || 
                (strcasecmp(\Helpers\Helpers::ref_classs_to_root($required_class), \Helpers\Helpers::ref_classs_to_root(static::class))===0);        
        
        return $result;
    }

}
