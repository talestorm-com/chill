<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentRibbon;

/**
 * Description of MediaContentRibbonTagTEXT
 *
 * @author eve
 * @property string $file_version
 * @property MediaContentRibbonItem[] $items
 */
class MediaContentRibbonSearchTEXT implements \common_accessors\IMarshall, \Countable, \Iterator {

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

    /** @var MediaContentRibbonItem[] */
    protected $items;

    /** @return string */
    protected function __get__file_version() {
        return $this->file_version;
    }

    /** @return MediaContentRibbonItem[] */
    protected function __get__items() {
        return $this->items;
    }

    protected function __construct(string $search_query, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $this->file_version = static::get_file_version();
        $this->items = [];
        $this->load($search_query, $offset, $perpage, $language, $default_language);
        $this->cache($search_query, $offset, $perpage, $language);
    }

    protected function cache(string $search_query, int $offset, int $perpage, \Language\LanguageItem $language) {
        $cache = \Cache\FileCache::F();
        $cache_key = static::mk_cache_key($search_query, $offset, $perpage, $language);
        //
    }

    protected static function mk_cache_key(string $search_query, int $offset, int $perpage, \Language\LanguageItem $language) {
        return implode("---", [__CLASS__, $search_query, $offset, $perpage, $language->id]);
    }

    protected function load(string $search_query, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $query = "
            SELECT A.id,A.id content_id,A.ctype content_type,A.enabled,
            ATAG.tag_id, COALESCE(TAGSTR1.name,TAGSTR2.name) tag_name,
            COALESCE(TXSTRINGS1.name,TXSTRINGS2.name) name,
            'media_content_poster' image_context,
            A.id image_owner,
             TXLINK.default_poster image,
            -- выборка характеристик TEXT
             COALESCE(TXSTRINGS1.intro,TXSTRINGS2.intro) text_short_text,
            TXLINK.post news_post,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars,
            NULL as dmy
            FROM  media__content A 
            LEFT JOIN media__content__tag__list ATAG ON(ATAG.media_id=A.id AND ATAG.sort=0)
            LEFT JOIN media__content__tag__strings TAGSTR1 ON(TAGSTR1.id=ATAG.tag_id AND TAGSTR1.language_id='%s')
            LEFT JOIN media__content__tag__strings TAGSTR2 ON(TAGSTR2.id=ATAG.tag_id AND TAGSTR2.language_id='%s')
             JOIN media__content__text TXLINK ON(TXLINK.id=A.id)
            LEFT JOIN media__content__text__strings__lang_%s TXSTRINGS1 ON(TXSTRINGS1.id=TXLINK.id )
            LEFT JOIN media__content__text__strings__lang_%s TXSTRINGS2 ON(TXSTRINGS2.id=TXLINK.id )
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=A.id)
            WHERE A.enabled=1 AND  COALESCE(TXSTRINGS1.name,TXSTRINGS2.name) LIKE :Q
            ORDER BY A.id DESC
            LIMIT {$perpage} OFFSET {$offset};
            "; //LIMIT //LIMIT {$perpage} OFFSET {$offset}
        $cc = mb_substr_count($query, "%s");
        $cap = [];
        while (count($cap) < $cc) {
            $cap[] = $language;
            $cap[] = $default_language;
        }
        $aquery = call_user_func_array('sprintf', array_merge([$query], $cap));
        $rows = \DB\DB::F()->queryAll($aquery, [":Q" => "%{$search_query}%"]);
        $this->items = [];
        foreach ($rows as $row) {
            try {
                $this->items[] = MediaContentRibbonItem::F($row);
            } catch (\Throwable $e) {
                
            }
        }
    }

    /**
     * 
     * @param string $search_query
     * @param int $offset
     * @param int $perpage
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return \static
     */
    public static function F(string $search_query, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $cs = static::class;
        $cache = \Cache\FileCache::F();
        $cache_key = static::mk_cache_key($search_query, $offset, $perpage, $language);
        $x = $cache->get($cache_key);
        if ($x && is_object($x) && ($x instanceof $cs) && ($x->file_version === static::get_file_version())) {
            return $x;
        }
        return new static($search_query, $offset, $perpage, $language, $default_language);
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

}
