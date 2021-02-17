<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent;

/**
 * Description of MediaContentTypeList
 *
 * @author eve
 */
class MediaContentTypeList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator,
        \common_accessors\TDefaultMarshaller;

    private static $instance = null;

    /** @var MediaContentType[] */
    private $items;
    private $class_version;

    public static function get__class_version() {
        $ini_name = static::get_ini_filename();
        return md5(implode("*", [__FILE__, filemtime(__FILE__), MediaContentType::get_class_version(), $ini_name, filemtime($ini_name)]));
    }

    private static function get_ini_filename() {
        return __DIR__ . DIRECTORY_SEPARATOR . "MediaContentTypeListConfig.php";
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    private function __construct() {
        static::$instance = $this;
        $this->items = [];
        $this->class_version = static::get__class_version();
        $this->load();
        $this->cache();
    }

    private function load() {
        $values = include static::get_ini_filename();
        is_array($values) && count($values) ? 0 : \Errors\common_error::R("cant load content type vocabulary");
        foreach ($values as $value) {
            if (is_array($value)) {
                $item = MediaContentType::F($value);
                $this->items[] = $item;
            }
        }
    }

    private function cache() {
        $cache = \Cache\FileCache::F();
        $cache_key = __CLASS__;
        $cache->put($cache_key, $this, 0);
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : static::FACTORY();
    }

    /**
     * 
     * @return \static
     */
    protected static function FACTORY() {
        $cache_key = __CLASS__;
        $cache = \Cache\FileCache::F();
        $x = $cache->get($cache_key);
        $cs = __CLASS__;
        if ($x && is_object($x) && ($x instanceof $cs) && ($x->class_version === static::get__class_version())) {
            static::$instance = $x;
            return static::$instance;
        }
        return new static();
    }

}
