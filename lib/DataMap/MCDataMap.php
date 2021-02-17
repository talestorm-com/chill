<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

/**
 * Description of MCDataMap
 *
 * @author eve
 */
class MCDataMap implements IDataMap {

    const DEFAULT_TIMEOUT = 600;

    /** @var \Memcached */
    private $mmc = null;
    private static $instance = null;

    private function __construct() {
        static::$instance = $this;
        $this->mmc = new \Memcached();
        $this->mmc->addServer("127.0.0.1", 11211);
    }

    public function exists(string $key): bool {
        $item = $this->mmc->get($key);
        if ($this->mmc->getResultCode() === \Memcached::RES_SUCCESS) {
            return true;
        }
        return false;
    }

    public function get(string $key, $default = null) {
        $item = $this->mmc->get($key);
        if ($this->mmc->getResultCode() === \Memcached::RES_SUCCESS) {
            return $item;
        }
        return $default;
    }

    public function get_all_cloned(): array {
        return [];
    }

    public function get_filtered(string $key, array $filters = array(), \Filters\IFilterParamSet $params = null) {
        return \Filters\FilterManager::F()->apply_chain($this->get($key), $filters, $params);
    }

    public function get_filtered_def(string $key, array $filters = array(), \Filters\IParamPool $parampool = null) {
        return $this->get_filtered($key, $filters, $parampool && $parampool->has_params_for_property() ? $parampool->get_param_set_for_property() : null);
    }

    public function rebind(array &$source): IDataMap {
        return $this;
    }

    public function remove(string $key): IDataMap {
        $this->mmc->delete($key);
        return $this;
    }

    public function set(string $key, $value): IDataMap {
        if (!($value === null || is_scalar($value))) {
            \Errors\common_error::RF("%s can only store scalar values!", __CLASS__);
        }
        $this->mmc->set($key, $value, static::DEFAULT_TIMEOUT);
        return $this;
    }
    public function set_with_timeout(string $key, $value,int $timeout): MCDataMap {
        if (!($value === null || is_scalar($value))) {
            \Errors\common_error::RF("%s can only store scalar values!", __CLASS__);
        }
        $this->mmc->set($key, $value, $timeout);
        return $this;
    }

    public static function F(): MCDataMap {
        return static::$instance ? static::$instance : new static();
    }
    
    
    public function touch(string $key, int $timeout = null): MCDataMap{
        $this->mmc->touch($key, $timeout === null ?static::DEFAULT_TIMEOUT:$timeout);        
        return $this;
    }

}
