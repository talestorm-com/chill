<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\notificator;

/**
 * @property int $id
 * @property \DateTime $time
 * @property int $state
 * @property string $version
 * @property bool $valid
 * @property int $timestamep
 */
class ImportLogNotificator implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller, 
        \common_accessors\TCommonImport; 

    const CACHE_DEPENDENCY_ID = "data_import_notificator";

    private static $instance;
    //<editor-fold defaultstate="collapsed" desc="props and getters">
    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $id;

    /** @var \DateTime */
    protected $time;

    /** @var int */
    protected $state;

    /** @var string */
    protected $version;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return \DateTime */
    protected function __get__time() {
        return $this->time;
    }

    /** @return int */
    protected function __get__state() {
        return $this->state;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    /** @return bool */
    protected function __get__valid() {
        return ($this->id && $this->time) ? true : false;
    }

    protected function __get__timestamp() {
        return $this->time ? $this->time->getTimestamp() : 0;
    }

    //</editor-fold>
    //</editor-fold>

    protected function __construct() {
        static::$instance = $this;
        $this->version = static::get_file_ver();
        $this->load();
        if ($this->valid) {
            $this->set_cache();
        }
    }

    protected function set_cache() {
        $cache = \Cache\FileCache::F();
        $cache->put(__CLASS__, $this, 0, \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY_ID));
    }

    public static function RESET_CACHE() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY_ID)->reset_dependency_beacons();
        static::$instance = null;
    }

    protected function load() {
        $query = "SELECT *,updated time FROM data_import_log ORDER BY id DESC LIMIT 1 OFFSET 0;";
        $row = \DB\DB::F()->queryRow($query);
        $this->import_props(is_array($row) ? $row : []);
        
    }

    protected function t_common_import_get_filters() {
        return [
            'id' => ["IntMore0", 'DefaultNull'],
            'time' => ["DateMatch", "DefaultNull"],
            'state' => ["Int", 'DefaultNull']
        ];
        
        
    }

    protected function t_common_import_after_import() {
        if (null === $this->id) {
            $this->id = -1;
        }
    }

    protected static function get_file_ver() {
        return md5(implode("-", [__FILE__, filemtime(__FILE__)]));
    }

    /**
     * 
     * @return \DataImport\notificator\ImportLogNotificator
     */
    public static function F(): ImportLogNotificator {
        return static::$instance ? static::$instance : new static();
    }

    /**
     * 
     * @return \DataImport\notificator\ImportLogNotificator
     */
    public static function C(): ImportLogNotificator {
        return static::$instance ? static::$instance : static::factory();
    }

    protected static function factory() {
        $value = \Cache\FileCache::F()->get(__CLASS__); /* @var $value static */
        $cs = get_called_class();
        if ($value && is_object($value) && ($value instanceof $cs) && $value->version === static::get_file_ver()) {
            static::$instance = $value;
        }
        return static::F();
    }

    protected function t_default_marshaller_export_property_time() {
        return $this->time ? $this->time->getTimestamp() : 0;
    }

}
