<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets;

/**
 * @property string $version_key  
 */
class VersionMonitor {    

    use \common_accessors\TCommonAccess;

    /** @var VersionMonitor */
    protected static $instance;
    protected $version_key;

    protected function __construct() {
        static::$instance = $this;
        $this->version_key = implode("", ["a", filemtime(__FILE__)]);
    }

    protected function __get__version_key() {
        return $this->version_key;
    }

    /**
     * 
     * @return VersionMonitor
     */
    public static function F(): VersionMonitor {
        return static::$instance ? static::$instance : new static();
    }

    public function touch() {
        @touch(__FILE__);
    }

}
