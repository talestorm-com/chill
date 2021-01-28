<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of LanguageList
 *
 * @author eve
 * @property LanguageItem[] $items
 * @property LanguageItem[] $index
 * @property string $class_version
 */
class LanguageList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    CONST CACHE_DEPENDENCY_KEY = "LANGUAGE_LIST";

    private static $instance;
    private static $_class_version = null;
    protected $items;
    protected $index;
    protected $class_version;

//<editor-fold defaultstate="collapsed" desc="getters">

    /** @return LanguageItem[] */
    protected function __get__items() {
        return $this->items;
    }

    protected function __get__index() {
        return $this->index;
    }

    /** @return string */
    protected function __get__class_version() {
        return $this->class_version;
    }

//</editor-fold>



    public static function get_class_version() {
        if (!static::$_class_version) {
            static::$_class_version = md5(implode("+", [__FILE__, filemtime(__FILE__), LanguageItem::get_class_version()]));
        }
        return static::$_class_version;
    }

    private function __construct() {
        static::$instance = $this;
        $this->class_version = static::get_class_version();
        if (!$this->load_cache()) {
            $this->load_db();
            $this->keep_cache();
        }
        $this->reindex();
    }

    protected function load_cache() {

        $cache = \Cache\FileCache::F();
        $key = __CLASS__;
        $value = $cache->get($key);
        if ($value && is_array($value) && array_key_exists("class_version", $value) && array_key_exists("items", $value) && is_array($value["items"]) && count($value['items']) && $value["class_version"] === $this->class_version) {
            $this->items = $value["items"];
            return true;
        }
        return false;
    }

    protected function load_db() {
        $rows = \DB\DB::F()->queryAll("SELECT * FROM language__language ORDER BY sort,id");
        $this->items = [];
        foreach ($rows as $row) {
            try {
                $item = LanguageItem::F()->load_array($row);
                $this->items[] = $item;
            } catch (\Throwable $e) {
                
            }
        }
    }

    protected function keep_cache() {
        $cache = \Cache\FileCache::F();
        $key = __CLASS__;
        $cache->put($key, ['items' => $this->items, 'class_version' => $this->class_version], 0, \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY_KEY));
    }

    protected function reindex() {
        $this->index = [];
        foreach ($this->items as $item) {
            $this->index[$item->id] = $item;
        }
    }

    /**
     * 
     * @param string $id
     * @return LanguageItem|null
     */
    public function get_language(string $id) {
        return array_key_exists($id, $this->index) ? $this->index[$id] : null;
    }

    /**
     * 
     * @return LanguageItem|null
     */
    protected function get_fallback_language() {
        return count($this->items) ? $this->items[0] : null;
    }

    /**
     * 
     * @return LanguageItem|null
     */
    public function get_default_language() {
        $default_language_id = \PresetManager\PresetManager::F()->get_filtered("PREF_DEFAULT_LANGUAGE", ["Strip", "Trim", "NEString", "DefaultNull"]);
        $default_language_id = $default_language_id ? $default_language_id : "ru";
        $result = $this->get_language($default_language_id);
        return $result ? $result : $this->get_fallback_language();
    }

    /**
     * 
     * @return LanguageItem|null
     */
    public function get_current_language() {
        $current_language_id = \DataMap\CookieDataMap::F()->get_filtered("content_language", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        return $current_language_id ? $this->get_language($current_language_id) : $this->get_default_language();
    }

    /**
     * 
     * @return \Language\LanguageList
     */
    public static function F(): LanguageList {
        return static::$instance ? static::$instance : new static();
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    public function reset_cache() {
        static::reset_cached();
    }

    public static function reset_cached() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY_KEY)->reset_dependency_beacons();
        static::$instance = null;
    }

}
