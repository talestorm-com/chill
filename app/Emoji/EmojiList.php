<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Emoji;

/**
 * Description of EmojiList
 *
 * @author eve
 * @property  EmojiListItem[] $items
 * @property string $class_version
 */
class EmojiList implements \Countable, \Iterator, \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    CONST CACHE_DEPENDENCY_BEACON = "EMOJI_LIST";

    private static $_class_version;

    public static function get_class_version() {
        if (!static::$_class_version) {
            static::$_class_version = md5(implode("*", [__FILE__, filemtime(__FILE__), EmojiListItem::get_class_version()]));
        }
        return static::$_class_version;
    }

    /** @var EmojiListItem[] */
    protected $items;

    /** @var string */
    protected $class_version;
    protected static $instance = null;

    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return EmojiListItem[] */
    protected function __get__items() {
        return $this->items;
    }

    /** @return string */
    protected function __get__class_version() {
        return $this->class_version;
    }

    //</editor-fold>


    private function __construct() {
        $this->class_version = static::get_class_version();
        static::$instance = $this;
        $this->items = [];
        if (!$this->load_cached()) {
            $this->load();
            $this->save_cached();
        }
    }

    private function load_cached() {
        $cache = \Cache\FileCache::F();
        $key = __CLASS__;
        $item = $cache->get($key);
        if ($item) {
            if (is_array($item) && array_key_exists("items", $item) && array_key_exists("class_version", $item) && $item["class_version"] === $this->class_version) {
                $this->items = $item["items"];
                return true;
            }
        }
        return false;
    }

    private function save_cached() {
        $cache_key = __CLASS__;
        $cache = \Cache\FileCache::F();
        $cache->put($cache_key, ["items" => $this->items, "class_version" => $this->class_version], 0, \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY_BEACON));
    }

    private function load() {
        $rows = \DB\DB::F()->queryAll("SELECT * FROM media__emoji ORDER BY sort,id DESC");
        $raw_rows = []; /* @var $raw_rows EmojiListItem[] */
        foreach ($rows as $row) {
            try {
                $item = EmojiListItem::F()->load_array($row);
                $raw_rows[":P{$item->id}"] = $item;
            } catch (\Throwable $e) {
                
            }
        }
        $names = \DB\DB::F()->queryAll("SELECT * FROM media__emoji__strings");
        foreach ($names as $name) {
            try {
                $cname = Filters\FilterManager::F()->apply_filter_array($name, ['id' => ['IntMore0'], 'language_id' => ['Strip', 'Trim', 'NEString'], 'name' => ['Strip', 'Trim', 'NEString']]);
                \Filters\FilterManager::F()->raise_array_error($cname);
                $key = "P{$cname['id']}";
                if (array_key_exists($key, $raw_rows)) {
                    $raw_rows[$key]->add_name($cname["language_id"], $cname["name"]);
                }
            } catch (\Throwable $e) {
                
            }
        }
        $this->items = [];
        foreach ($raw_rows as $row) {
            $this->items[$row->tag] = $row;
        }
    }

    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    public static function reset_cache() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY_BEACON)->reset_dependency_beacons();
        static::$instance = null;
    }

}
