<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace __bootstrap;

class Loader {

    /** @var Loader */
    private static $instance;

    private function __construct() {
        static::$instance = $this;
        spl_autoload_register([$this, '_load'], true, true);
    }

    public function _load($class_name) {
        $base_dir = __DIR__ . DIRECTORY_SEPARATOR;
        $app_dir = realpath("{$base_dir}.." . DIRECTORY_SEPARATOR . "app").DIRECTORY_SEPARATOR;
        $class_route = ltrim(str_ireplace(['\\', '/'], DIRECTORY_SEPARATOR, $class_name), DIRECTORY_SEPARATOR) . ".php";
        $class_path = $app_dir . $class_route;        
        if (file_exists($class_path)) {            
            require_once $class_path;
        } else {
            $class_path = $app_dir . "vendor" . DIRECTORY_SEPARATOR . $class_route;            
            if (file_exists($class_path)) {                
                require_once $class_path;
            } else {
                $class_path = $base_dir . $class_route;
                if (file_exists($class_path)) {
                    require_once $class_path;
                } else {
                    $base_dir = __DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR;
                    $class_path = $base_dir . $class_route;
                    if (file_exists($class_path)) {
                        require_once $class_path;
                    }
                }
            }
        }
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

}

Loader::F();
