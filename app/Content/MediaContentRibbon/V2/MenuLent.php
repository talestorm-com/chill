<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentRibbon\V2;

/**
 * Description of RibbonLent
 *
 * @author eve
 */
class MenuLent extends \Content\Content {

    CONST PAGE_MULTER = 1;    
    CONST BANNERS_PER_PAGE = 3;
    CONST COLLECTIONS_PER_PAGE = 10;

    /** @var MediaContentRibbonItem[] */
    protected $items;

    protected function __get__items() {
        return $this->items;
    }

    protected static $_file_version;

    /** @var string */
    protected $file_version;

    protected function __get__file_version() {
        return $this->file_version;
    }

    public static function get_file_version() {
        if (!static::$_file_version) {
            static::$_file_version = md5(implode("-", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_file_version;
    }

    protected function __construct(\Language\LanguageItem $language, int $page) {
        $this->file_version = static::get_file_version();
        $this->load($language, $page);
        $this->cache($language, $page);
    }

    protected function load(\Language\LanguageItem $language, int $page) {
        
        $default_language = \Language\LanguageList::F()->get_default_language();               
        $banners = $this->load_banner($language, $default_language, $page);
        $collections = $this->load_collection($language, $default_language, $page);
        $result = [];
        for ($i = 0; $i < static::PAGE_MULTER; $i++) {          
            $result = array_merge($result, array_slice($banners, $i, 3));                        
            $result = array_merge($result, array_slice($collections, $i, 10));
           
        }
        $this->items = $result;
    }

    protected function load_collection(\Language\LanguageItem $language, \Language\LanguageItem $default_language, int $page) {
        $offset = static::COLLECTIONS_PER_PAGE * $page;
        $common_media_context = \Content\MediaContent\Readers\ctCOLLECTION\MediaContentObject::MEDIA_CONTEXT;
        $query_t = "SELECT
            SQL_CALC_FOUND_ROWS
            A.id,A.id content_id,A.ctype content_type,A.enabled,
            ATAG.tag_id, COALESCE(TAGSTR1.name,TAGSTR2.name) tag_name,
            COALESCE(COLLECTIONSTRINGS1.name,COLLECTIONSTRINGS2.name) name,
            '{$common_media_context}' image_context,
            A.id image_owner,
            COLLECTIONLINK.default_poster image,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars,
            NULL as dmy
            FROM  media__content A 
            LEFT JOIN media__content__tag__list ATAG ON(ATAG.media_id=A.id AND ATAG.sort=0)
            LEFT JOIN media__content__tag__strings TAGSTR1 ON(TAGSTR1.id=ATAG.tag_id AND TAGSTR1.language_id='%s')
            LEFT JOIN media__content__tag__strings TAGSTR2 ON(TAGSTR2.id=ATAG.tag_id AND TAGSTR2.language_id='%s')
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=A.id)
            -- линки для коллекции
            LEFT JOIN media__content__collection COLLECTIONLINK ON(COLLECTIONLINK.id=A.id)
            LEFT JOIN media__content__collection_strings_lang_%s COLLECTIONSTRINGS1 ON(COLLECTIONSTRINGS1.id=COLLECTIONLINK.id )
            LEFT JOIN media__content__collection_strings_lang_%s COLLECTIONSTRINGS2 ON(COLLECTIONSTRINGS2.id=COLLECTIONLINK.id )
            WHERE A.ctype = 'ctCOLLECTION' AND A.enabled=1
            ORDER BY A.mcsort,A.id DESC            
    ";
        return $this->load_query_to_result($query_t, $language, $default_language, static::COLLECTIONS_PER_PAGE, $offset);
    }

    protected function load_banner(\Language\LanguageItem $language, \Language\LanguageItem $default_language, int $page) {
        $offset = static::BANNERS_PER_PAGE * $page;
        $common_media_context = \Content\MediaContent\Readers\ctBANNER\MediaContentObject::MEDIA_CONTEXT;
        $query_t = "
             SELECT SQL_CALC_FOUND_ROWS
             A.id,A.id content_id,A.ctype content_type,A.enabled,
             ATAG.tag_id, COALESCE(TAGSTR1.name,TAGSTR2.name) tag_name,
            'banner' name,
            '{$common_media_context}' image_context,
            A.id image_owner,
            BANNERLINK.default_poster image,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars,
            --  выборка характеристик баннера
            BANNERLINK.background  banner_background_color,
            BANNERLINK.text_color  banner_foreground_color,
            COALESCE(BANNERSTRINGS1.url,BANNERSTRINGS2.url)banner_url,
            COALESCE(BANNERSTRINGS1.bannertext,BANNERSTRINGS2.bannertext) banner_text,   
            NULL as dmy
            FROM media__content A 
            LEFT JOIN media__content__tag__list ATAG ON(ATAG.media_id=A.id AND ATAG.sort=0)
            LEFT JOIN media__content__tag__strings TAGSTR1 ON(TAGSTR1.id=ATAG.tag_id AND TAGSTR1.language_id='%s')
            LEFT JOIN media__content__tag__strings TAGSTR2 ON(TAGSTR2.id=ATAG.tag_id AND TAGSTR2.language_id='%s')
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=A.id)
            LEFT JOIN media__content__banner BANNERLINK ON(BANNERLINK.id=A.id)
            LEFT JOIN media__content__banner__strings BANNERSTRINGS1 ON(BANNERSTRINGS1.id=BANNERLINK.id AND BANNERSTRINGS1.language_id='%s')
            LEFT JOIN media__content__banner__strings BANNERSTRINGS2 ON(BANNERSTRINGS2.id=BANNERLINK.id AND BANNERSTRINGS2.language_id='%s')
            WHERE A.ctype = 'ctBANNER' AND A.enabled=1
            ORDER BY A.mcsort, A.id DESC
            ";
        return $this->load_query_to_result($query_t, $language, $default_language, static::BANNERS_PER_PAGE, $offset);
    }

    

    protected function load_query_to_result(string $query_t, \Language\LanguageItem $language, \Language\LanguageItem $default_language, int $qty, int $offset) {
        $cc = mb_substr_count($query_t, "%s");
        $cap = [];
        while (count($cap) < $cc) {
            $cap[] = $language;
            $cap[] = $default_language;
        }
        $query = call_user_func_array('sprintf', array_merge([$query_t], $cap)) . " LIMIT %s OFFSET %s ;";        
        $select = \DB\DB::F()->queryAll(sprintf($query, $qty, $offset), []);
        $total = \DB\DB::F()->queryScalari("SELECT FOUND_ROWS();");
        if (!count($select)) { // нет вообще ничего - вышли за предел, прикидываем какая страница нам нужна         
            $overlap = intval(floor($offset / $total)); // сколько циклов вышло впустую
            $o2 = $offset - ($overlap * $total); // новый оффсет
            $select = \DB\DB::F()->queryAll(sprintf($query, $qty, $o2), []); // загружаем с оффсета          
        }
        if (count($select) < $qty) { //если что-то есть, но мало (или опять ничего нет)
            // значит мы у границы и надо догрузить с носу сколько там надо
            // все равно понадобится повторная проверка - на случай если их там в принципе мало
            $select2 = \DB\DB::F()->queryAll(sprintf($query, $qty - count($select), 0), []); // догружаем с начала недостающее количество
            $select = array_merge($select, $select2);
        }
        if (count($select) && count($select) < $qty) { // догруза не вышно
            while (count($select) < $qty) { //добираем хвостиками
                $select = array_merge($select, $select);
            }
            $select = array_slice($select, 0, $qty); // обрезаем хвостик
        }
        $result = [];
        foreach ($select as $row) {
            $result[] = \Content\MediaContentRibbon\MediaContentRibbonItem::F($row);
        }
        return $result;
    }

    protected function cache(\Language\LanguageItem $language, int $page) {
        $key = static::mk_cache_key($language, $page);
        $cache = \Cache\FileCache::F();
        //$cache->put($key, $this, 0, \Cache\FileBeaconDependency::F([]));
    }

    protected static function mk_cache_key(\Language\LanguageItem $language, int $page) {
        return implode(":::", [__CLASS__, $language->id, $page, $page, 1 ? "T" : "F"]);
    }

    /**
     * 
     * @param \Language\LanguageItem $language
     * @param int $offset
     * @param int $perpage
     * @return \static
     */
    public static function F(\Language\LanguageItem $language, int $page = 0) {
        $cache_key = static::mk_cache_key($language, $page);
        $cached = \Cache\FileCache::F()->get($cache_key);
        $cs = static::class;
        if ($cached && is_object($cached) && ($cached instanceof $cs) && $cached->file_version === static::get_file_version()) {
            return $cached;
        }
        return new static($language, $page);
    }

}
