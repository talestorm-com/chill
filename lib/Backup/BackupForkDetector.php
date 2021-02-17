<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup;

/**
 * @property bool $can_fork
 */
final class BackupForkDetector {

    use \common_accessors\TCommonAccess;

    /** @var BackupForkDetector */
    protected static $instance;

    /** @var bool */
    protected $_can_fork = null;

    protected function __get__can_fork() {
        if (null === $this->_can_fork) {
            $this->_can_fork = function_exists('pcntl_fork');
        }
        return $this->_can_fork;
    }

    protected function __construct() {
        static::$instance = $this;
    }

    /**
     * 
     * @return \Backup\BackupForkDetector
     */
    public static function F(): BackupForkDetector {
        return static::$instance ? static::$instance : new static();
    }

}
