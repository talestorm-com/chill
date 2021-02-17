<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Basket;

/**
 * @property OfflineShop[] $items
 * @property string $version
 */
class OfflineShopList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;  

    CONST CACHE_DEPENDENCY = "offline_shop";

    /** @var OfflineShop[] */
    protected $items;

    /** @var string */
    protected $version;

    protected function __get__items() {
        return $this->items;
    }

    protected function __get__version() {
        return $this->version;
    }

    /**
     * 
     * @param int $shop_id
     * @param type $default
     * @return OfflineShop
     */
    public function get_by_id(int $shop_id, $default = null) {        
        foreach ($this->items as $item){
            if($item->id===$shop_id){
                return $item;
            }
        }
        return $default;
    }

    protected function __construct() {
        $this->items = [];
        $this->version = static::get_file_ver();
        $this->load();
        $this->set_cache();
    }

    protected function load() {
        $query = "SELECT * FROM storage__offline__shop WHERE visible=1 AND storage_id IS NOT NULL;";
        $rows = \DB\DB::F()->queryAll($query);
        foreach ($rows as $row) {
            $item = OfflineShop::F($row);
            $item && $item->valid ? $this->items[] = $item : 0;
        }
        return $this;
    }

    protected function set_cache() {
        \Cache\FileCache::F()->put(__CLASS__, $this, 0, \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY));
        return $this;
    }

    protected static function get_file_ver() {
        return md5(implode("-", [__FILE__, filemtime(__FILE__)]));
    }

    /**
     * 
     * @return \Basket\OfflineShopList
     */
    public static function F(): OfflineShopList {
        return new static();
    }

    /**
     * 
     * @return \Basket\OfflineShopList
     */
    public static function C(): OfflineShopList {
        $cache = \Cache\FileCache::F();
        $item = $cache->get(__CLASS__); /* @var $item static */
        $ps = static::class;
        if ($item && is_object($item) && ($item instanceof $ps) && $item->version === static::get_file_ver()) {
            return $item;
        }
        return static::F();
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    public static function reset_cache() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY)->reset_dependency_beacons();
    }

}
