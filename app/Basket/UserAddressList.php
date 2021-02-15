<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Basket;

/**
 * Description of UserAddressList
 *
 * @author studio2
 * @property UserAddress[] $items
 * @property string $version
 * @property int $user_id
 */
class UserAddressList implements \common_accessors\IMarshall, \Iterator, \Countable {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    const CACHE_DEPENDENCY_KEY = "user_address";

    /** @var UserAddress[] */
    protected $items;

    /** @var string */
    protected $version;

    /** @var int */
    protected $user_id;

    /** @return UserAddress[] */
    protected function __get__items() {
        return $this->items;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    /** @return int */
    protected function __get__user_id() {
        return $this->user_id;
    }

    protected function __construct(int $id) {
        $this->version = static::file_ver();
        $this->user_id = $id;
        $this->load();
        $this->cache();
    }

    protected function load() {
        $query = "SELECT uid,label,address FROM user__address WHERE id=:P ORDER BY label,uid LIMIT 25 OFFSET 0;";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $this->user_id]);
        $this->items = [];
        foreach ($rows as $row) {
            $item = UserAdress::F($row);
            if ($item && $item->valid) {
                $this->items[] = $item;
            }
        }
    }

    protected function cache() {
        \Cache\FileCache::F()->put(static::cache_key($this->user_id), $this, 86400, \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY_KEY));
    }

    /**
     * 
     * @param int $user_id
     * @return \static
     */
    public static function F(int $user_id) {
        return new static($user_id);
    }

    protected static function file_ver() {
        return md5(implode("-", [__FILE__, filemtime(__FILE__)]));
    }

    protected static function cache_key(int $id) {
        return implode("-------", [__CLASS__, $id]);
    }

    /**
     * 
     * @param int $user_id
     * @return \static
     */
    public static function C(int $user_id) {
        $item = \Cache\FileCache::F()->get(static::cache_key($user_id)); /* @var $item static */
        $cs = static::class;
        if ($item && is_object($item) && ($item instanceof $cs) && $item->version === static::file_ver()) {
            return $item;
        }
        return static::F($user_id);
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    public static function reset_cache() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY_KEY)->reset_dependency_beacons();
    }

}
