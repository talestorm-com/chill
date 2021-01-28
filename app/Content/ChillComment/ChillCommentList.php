<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ChillComment;

/**
 * Description of ChillCommentList
 *
 * @author eve
 * @property ChillCommentItem[] $items
 * @property string $class_version
 */
class ChillCommentList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    protected static $instance;

    /** @var ChillCommentItem[] */
    protected $items;

    /** @var string */
    protected $class_version;

    /** @return ChillCommentItem[] */
    protected function __get__items() {
        return $this->items;
    }

    /** @return string */
    protected function __get__class_version() {
        return $this->class_version;
    }

    public static function get_class_version() {
        return md5(implode(",", [__FILE__, filemtime(__FILE__)]));
    }

    protected function __construct(int $page = 0, int $perpage = 100) {
        static::$instance = $this;
        $this->class_version = static::get_class_version();
        $this->load($page, $perpage);
        $this->cache($page, $perpage);
    }

    /**
     * 
     * @return $this
     */
    protected function load(int $page = 0, int $perpage = 100) {
        $offset = $page * $perpage;
        $query = "SELECT A.*, S.cdn_url sticker_url,B.rating,S.name sticker_name
            FROM chill__review A LEFT JOIN chill__review__rating B ON(A.id=B.id)
            LEFT JOIN chill__review__sticker S ON(S.id=A.sticker)
            WHERE A.enabled=1
            ORDER BY datum DESC,id DESC
            LIMIT {$perpage} OFFSET {$offset}";
        $rows = \DB\DB::F()->queryAll($query);
        foreach ($rows as $row) {
            $item = ChillCommentItem::FA($row);
            $item->valid ? $this->items[] = $item : 0;
        }
        return $this;
    }

    /**
     * 
     * @return $this
     */
    protected function cache(int $page = 0, int $perpage = 100) {
        \Cache\FileCache::F()->put(implode('-a-', [__CLASS__, $page, $perpage]), $this, 3 * 60 * 60, \Cache\FileBeaconDependency::F([ChillCommentItem::CACHE_DEP, \Content\Stickers\StickerItem::CACHE_DEP]));
        return $this;
    }

    /**
     * 
     * @return \static
     */
    public static function F(int $page = 0, int $perpage = 100) {
        if (static::$instance) {
            return static::$instance;
        }
        $cs = static::class;
        $some = \Cache\FileCache::F()->get(implode("-a-", [__CLASS__, $page, $perpage]));
        if ($some && is_object($some) && ($some instanceof $cs) && $some->class_version === static::get_class_version()) {
            static::$instance = $some;
            return static::$instance;
        }
        return new static($page, $perpage);
    }

}
