<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Review;

/**
 * Description of ContentReviewsList
 *
 * @author eve
 * @property string $file_version
 * @property MediaReview[] $item
 */
class ContentReviewsList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    protected static $_file_version;

    public static function get_file_version() {
        if (!static::$_file_version) {
            static::$_file_version = md5(implode("-", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_file_version;
    }

    /** @var string */
    protected $file_version;

    /** @var MediaReview[] */
    protected $items;

    /** @return string */
    protected function __get__file_version() {
        return $this->file_version;
    }

    /** @return MediaReview[] */
    protected function __get__items() {
        return $this->items;
    }

    protected function __construct(int $media_id, int $qty, int $offset = 0) {
        $this->file_version = static::get_file_version();
        $this->items = [];
        $this->load($media_id, $qty, $offset);
        $this->cache($media_id, $qty, $offset);
    }

    protected function cache(int $media_id, int $qty, int $offset) {
        $cache = \Cache\FileCache::F();
        $cache_key = static::mk_cache_key($media_id, $qty, $offset);
        //
    }

    protected static function mk_cache_key(int $media_id, int $qty, int $offset) {
        return implode("---", [__CLASS__, $media_id, $qty, $offset]);
    }

    protected function load(int $media_id, int $qty, int $offset) {
        $query = "            
            SELECT  A.media_id,A.user_id,A.post,A.rate,A.info,UF.name
            FROM media__content__review A JOIN user__fields UF ON(UF.id=A.user_id)            
            WHERE A.approved=1 AND A.media_id=:Pmedia
            ORDER BY A.post DESC
            LIMIT {$qty} OFFSET {$offset};            
            "; 
        $rows = \DB\DB::F()->queryAll($query, [":Pmedia" => $media_id]);
        $this->items = [];
        foreach ($rows as $row) {
            try {
                $this->items[] = MediaReview::F($row);
            } catch (\Throwable $e) {
                
            }
        }
    }

    /**
     * 
     * @param int $emoji_id
     * @param int $offset
     * @param int $perpage
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return \static
     */
    public static function F(int $media_id, int $qty, int $offset = 0) {
        $cs = static::class;
        $cache = \Cache\FileCache::F();
        $cache_key = static::mk_cache_key($media_id, $qty, $offset);
        $x = $cache->get($cache_key);
        if ($x && is_object($x) && ($x instanceof $cs) && ($x->file_version === static::get_file_version())) {
            return $x;
        }
        return new static($media_id, $qty, $offset);
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

}
