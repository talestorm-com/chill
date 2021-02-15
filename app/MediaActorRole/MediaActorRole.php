<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MediaActorRole;

/**
 * Description of MediaActorRole
 *
 * @author eve
 * @property string[] $items
 */
class MediaActorRole implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    /** @var string[] */
    protected $items;

    /** @var string */
    protected $class_version;
    private static $_class_version;
    private static $instance;

    protected function __get__items() {
        return $this->items;
    }

    protected function __get__class_version() {
        return $this->class_version;
    }

    protected static function get_imar_version() {
        $file = __DIR__ . DIRECTORY_SEPARATOR . "IMediaActorRole.php";
        return implode("-", [$file, filemtime($file)]);
    }

    protected static function get_class_version() {
        if (!static::$_class_version) {
            static::$_class_version = md5(implode(";", [__FILE__, filemtime(__FILE__), static::get_imar_version()]));
        }
        return static::$_class_version;
    }

    protected function __construct() {
        $this->items = [];
        $this->class_version = static::get_class_version();
        $this->load();
        $this->keep_cache();
    }

    protected function load() {
        $class = new \ReflectionClass(IMediaActorRole::class);
        $cl = $class->getConstants();
        foreach ($cl as $const => $value) {
            $this->items[$const] = $value;
        }
    }

    protected function keep_cache() {
        $cache = \Cache\FileCache::F();
        $key = __CLASS__;
        $cache->put($key, $this);
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : static::factory();
    }

    private static function factory() {
        $cache = \Cache\FileCache::F();
        $key = __CLASS__;
        $value = $cache->get($key);
        $cs = static::class;
        if ($value && is_object($value) && ($value instanceof $cs) && $value->class_version === static::get_class_version()) {
            static::$instance = $value;
            return $value;
        }
        return new static();
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

}
