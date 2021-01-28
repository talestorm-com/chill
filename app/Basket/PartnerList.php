<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Basket;

/**
 * @property PartnerShop[] $items
 * @property string $version
 * @property string[] $townlist
 */
class PartnerList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    CONST CACHE_DEPENDENCY = "partner_shop";

    /** @var PartnerShop[] */
    protected $items;

    /** @var string */
    protected $version;
    protected $townlist;

    protected function __get__items() {
        return $this->items;
    }

    protected function __get__townlist() {
        return $this->townlist;
    }

    protected function __get__version() {
        return $this->version;
    }

    /**
     * 
     * @param int $shop_id
     * @param type $default
     * @return PartnerShop
     */
    public function get_by_id(int $shop_id, $default = null) {
        foreach ($this->items as $item) {
            if ($item->id === $shop_id) {
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
        $query = "SELECT * FROM storage__partners WHERE enabled=1;";
        $rows = \DB\DB::F()->queryAll($query);
        foreach ($rows as $row) {
            $item = PartnerShop::F($row);
            $item && $item->valid ? $this->items[] = $item : 0;
        }
        $townlist = [];
        foreach ($this->items as $item) {
            if (!array_key_exists($item->town_key, $townlist)) {
                $townlist[$item->town_key] = $item->town;
            }
        }
        $this->townlist = $townlist;
        uasort($this->townlist, function($a, $b) {
            return strcasecmp($a, $b);
        });

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
     * @return \Basket\PartnerList
     */
    public static function F(): PartnerList {
        return new static();
    }

    /**
     * 
     * @return \Basket\PartnerList
     */
    public static function C(): PartnerList {
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
