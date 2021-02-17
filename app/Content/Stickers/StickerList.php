<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Stickers;

/**
 * Description of StickerList
 * @property StickerItem[] $items
 * @propetry string $class_version;
 * @author eve
 */
class StickerList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var StickerList */
    protected static $instance;

    /** @var StickerItem[] */
    protected $items;

    protected function __get__items() {
        return $this->items;
    }

    /** @var String */
    protected $class_version;

    protected function __get__class_version() {
        return $this->class_version;
    }

    public static function get_class_version() {
        return md5(implode(",", [__FILE__, filemtime(__FILE__)]));
    }

    protected function __construct() {
        static::$instance = $this;
        $this->items = [];
        $this->class_version = static::get_class_version();
        $this->load();
        $this->cache();
    }

    protected function load() {
        $this->items = [];
        $q = "SELECT * FROM chill__review__sticker ;";
        $rows = \DB\DB::F()->queryAll($q);
        foreach ($rows as $row) {
            $item = StickerItem::FA($row);
            $item->valid ? $this->items[] = $item : 0;
        }
        return $this;
    }

    protected function cache() {
        \Cache\FileCache::F()->put(__CLASS__, $this, 0, \Cache\FileBeaconDependency::F([StickerItem::CACHE_DEP]));
        return $this;
    }

    public static function F() {
        if (static::$instance) {
            return static::$instance;
        }
        $some = \Cache\FileCache::F()->get(__CLASS__);
        $cs = static::class;
        if (is_object($some) && ($some instanceof $cs) && $some->class_version === static::get_class_version()) {
            static::$instance = $some;
            return static::$instance;
        }
        return new static();
    }
    
    
    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

}
