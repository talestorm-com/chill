<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Config;

final class Config extends abstract_config {

    /** @var Config */
    private static $instance = null;

    private function __construct(array $data) {
        $this->load($data);
        static::$instance = $this;
    }

    /**
     * initialize config instance
     * @param array $data
     * @return \Config\Config
     */
    public static function init_instance(array $data): Config {
        if (static::$instance) {
            config_error::R("config instance alredy exists");
        }
        return new static($data);
    }

    public static function F(): Config {
        if (!static::$instance) {
            config_error::R("config instance still not exists");
        }
        return static::$instance;
    }

}
