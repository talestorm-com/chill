<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable;

class autoloader {

    public static function autoload($cn) {
        $m = [];
        if (preg_match('/^\\\{0,}ADVTable\\\(?<cp>.{1,})$/', $cn, $m)) {
            $cp = str_ireplace(['\\', '/'], DIRECTORY_SEPARATOR, $m['cp']) . ".php";
            $path = rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $cp;
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    public static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    public static function F() {
        //затычка
    }

}

autoloader::register();
