<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\CatalogSizeDef;

/**
 * @property string $version 
 */
class CatalogSizeDefVoc implements \common_accessors\IMarshall, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;
    
    const CACHE_DEPENDENCY="size_def_vocabulary";

    /** @var CatalogSizeDefinition[] */
    protected $items;

    /** @var CatalogSizeDefinition[] */
    protected $index;

    /** @var CatalogSizeDef */
    protected static $instance;

    /** @var string */
    protected $version;

    protected function __get__version() {
        return $this->version;
    }

    protected static function get_file_ver(): string {
        return md5(implode("", [__FILE__, filemtime(__FILE__)]));
    }

    protected function __construct() {
        static::$instance = $this;
        $this->version = static::get_file_ver();
        $this->load();
        $this->index = null;
    }

    protected function load() {
        $rows = \DB\DB::F()->queryAll("SELECT * FROM catalog__size__alter__def;");
        $this->items = [];
        foreach ($rows as $row) {
            $item = CatalogSizeDefinition::F($row);
            $item && $item->valid ? $this->items[] = $item : false;
        }
        $cache = \Cache\FileCache::F();
        $cache->put(__CLASS__, $this, 0, \Cache\FileBeaconDependency::F('size_def_vocabulary'));
    }

    /**
     * 
     * @return \DataModel\CatalogSizeDef\CatalogSizeDefVoc
     */
    public static function F(): CatalogSizeDefVoc {
        if (static::$instance) {
            return static::$instance;
        }
        return static::factory();
    }

    protected static function factory(): CatalogSizeDefVoc {
        $cache = \Cache\FileCache::F();
        $data = $cache->get(__CLASS__);
        $class = static::class;
        if ($data && is_object($data) && ($data instanceof $class)) {
            /* @var $data CatalogSizeDefVoc */
            if ($data->version === static::get_file_ver()) {
                return $data;
            }
        }
        return new static();
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    public static function RESET_CACHE() {
        static::$instance = null;
        \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY)->reset_dependency_beacons();
    }

    public function __sleep() {
        return ['items', 'version'];
    }

    public function __wakeup() {
        $this->index = null;
    }

    protected function reindex() {
        if (null === $this->index) {
            $this->index = [];
            foreach ($this->items as $item) {
                $this->index["A{$item->id}"] = $item;
            }
        }
        return $this;
    }

    public function exists(int $id): bool {
        $this->reindex();
        $key = "A{$id}";
        return array_key_exists($key, $this->index);
    }

    /**
     * 
     * @param int $id
     * @param mixed $default
     * @return CatalogSizeDefinition
     */
    public function get_by_id(int $id, $default = null) {
        if ($this->exists($id)) {
            return $this->index["A{$id}"];
        }
        return $default;
    }

}
