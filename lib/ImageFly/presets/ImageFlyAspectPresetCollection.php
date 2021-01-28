<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly\presets;

/**
 * Description of ImageFlyAspectPresetCollection
 *
 * @author eve
 * @property ImageFlyAspectPreset[] $items;
 * @property string $class_version
 */
class ImageFlyAspectPresetCollection implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    private static $s_class_version = null;
    private static $instance = null;

    /** @var ImageFlyAspectPreset[] */
    private $items;
    private $class_version;

    protected function __get__items() {
        return $this->items;
    }

    protected static function get_preset_conf_name() {
        return __DIR__ . DIRECTORY_SEPARATOR . "ImageFlyAspectPresetConf.php";
    }

    public static function get_class_version() {
        if (!static::$s_class_version) {
            $pf = static::get_preset_conf_name();
            static::$s_class_version = md5(implode("-", [
                __FILE__, filemtime(__FILE__), ImageFlyAspectPreset::get_class_version(),
                $pf, filemtime($pf),
            ]));
        }
        return static::$s_class_version;
    }

    protected function __construct() {
        $this->class_version = static::get_class_version();
        $this->load();
        $this->cache();
        static::$instance = $this;
    }

    protected function load() {
        $this->items = [];
        $file = static::get_preset_conf_name();
        $raw = require $file;
        foreach ($raw as $row) {
            $item = ImageFlyAspectPreset::F($row);
            if ($item->valid) {
                $this->items[] = $item;
            }
        }
    }

    protected function cache() {
        $cache = \Cache\FileCache::F();
        $cache->put(__CLASS__, $this);
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : static::factory();
    }

    /**
     * 
     * @return \static
     */
    protected static function factory() {
        $cache = \Cache\FileCache::F();
        $value = $cache->get(__CLASS__);
        if ($value) {
            $cs = __CLASS__;
            if (is_object($value) && ($value instanceof $cs)) {
                /* @var $value static */
                if ($value->class_version === static::get_class_version()) {
                    static::$instance = $value;
                    return static::$instance;
                }
            }
        }
        return new static();
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

}
