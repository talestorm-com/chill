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
class RibbonLent extends \Content\Content {

    CONST PAGE_MULTER = 3;
    CONST SEASONS_PER_PAGE = 10 * 3;
    CONST GIFTRAILS_PER_PAGE = 2 * 3;
    CONST BANNERS_PER_PAGE = 1 * 3;
    CONST COLLECTIONS_PER_PAGE = 1 * 3;

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
        //загрузка должна включать в себя:
        //6 сериалов
        //2 x гиф или трейлер
        // баннер
        // 1 подборка
        // 6 сериалов
        // 2x гиф или трейлер        
        // итого  12 сериалов 4 гиф или трейлера 1 баннер 1 подборка
        $default_language = \Language\LanguageList::F()->get_default_language();
        $rows = [];
        $seasons = $this->load_seasons($language, $default_language, $page);
        $giftrailer = $this->load_gif_trailers($language, $default_language, $page);
        $banners = $this->load_banner($language, $default_language, $page);
        $collections = $this->load_collection($language, $default_language, $page);
        $result = [];
        for ($i = 0; $i < static::PAGE_MULTER; $i++) {
            $result = array_merge($result, array_slice($seasons, $i * 10, 4));
            $result = array_merge($result, array_slice($banners, $i, 1));
            $result = array_merge($result, array_slice($seasons, $i * 10 + 4, 2));
            $result = array_merge($result, array_slice($giftrailer, $i * 2, 2));
            $result = array_merge($result, array_slice($collections, $i, 1));
            $result = array_merge($result, array_slice($seasons, $i * 10 + 6, 4));
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
            ORDER BY A.id DESC            
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
            ORDER BY A.id DESC
            ";
        return $this->load_query_to_result($query_t, $language, $default_language, static::BANNERS_PER_PAGE, $offset);
    }

    protected function load_gif_trailers(\Language\LanguageItem $language, \Language\LanguageItem $default_language, int $page) {
        $offset = static::GIFTRAILS_PER_PAGE * $page;
        $common_media_context = \Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_POSTERS;
        $trailer_media_context = \Content\MediaContent\Readers\Trailer\MediaContentObject::MEDIA_CONTEXT;
        $query_t = "
             SELECT SQL_CALC_FOUND_ROWS 
             A.id,A.id content_id,A.ctype content_type,A.enabled,A.free,
            ATAG.tag_id, COALESCE(TAGSTR1.name,TAGSTR2.name) tag_name,
            CASE A.ctype               
               WHEN 'ctTRAILER' THEN COALESCE(TRAILERSTRINGS1.name,TRAILERSTRINGS2.name,'trailer')               
               WHEN 'ctGIF' THEN COALESCE(GIFSTRINGS1.name,GIFSTRINGS2.name)               
            END  name,
            CASE A.ctype
               WHEN 'ctTRAILER' THEN  '{$trailer_media_context}'
               ELSE '{$common_media_context}'               
            END image_context,
            A.id  image_owner,
            CASE A.ctype
               WHEN 'ctTRAILER' THEN TRAILERLINK.default_image
               WHEN 'ctGIF' THEN GIFLINK.default_poster               
            END image,
            CASE A.ctype
               WHEN 'ctTRAILER' THEN TRAILERLINK.vertical
               WHEN 'ctGIF' THEN 0             
            END vertical,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars,
            -- выборка характеристик трейлера
            CASE A.ctype WHEN 'ctTRAILER' THEN B0.id  ELSE NULL END trailed_content, 
            CASE A.ctype WHEN 'ctTRAILER' THEN B0.ctype  ELSE NULL END trailed_content_type, -- на какой тип контента указывает трейлер
            CASE A.ctype WHEN 'ctTRAILER' THEN COALESCE(TRAILERVIDEOSTRINGS1.name,TRAILERVIDEOSTRINGS2.name) ELSE NULL END trailed_video_name, -- наименование для видео на которое указывает трейлер
            CASE A.ctype WHEN 'ctTRAILER' THEN COALESCE( TRAILERSOAPSTRINGS1.name,TRAILERSOAPSTRINGS2.name ) ELSE NULL END trailed_soap_name, -- наименование сериала на котороый указывает трейлер
            CASE A.ctype WHEN 'ctTRAILER' THEN COALESCE(TRAILERSEASONSTRINGS1.name,TRAILERSEASONSTRINGS2.name) ELSE NULL END trailed_season_name, -- наименование сезона на который указывает трейлер
            CASE A.ctype WHEN 'ctTRAILER' THEN COALESCE( TRAILERSEASONSOAPSTRINGS1.name,TRAILERSEASONSOAPSTRINGS2.name) ELSE NULL END trailed_season_soap_name, -- наименование сериала для сезона на который указывает трейлер
            -- выборка характеристик GIF
            CASE A.ctype WHEN 'ctGIF' THEN GIFLINK.cdn_url ELSE NULL END gif_cdn_url,
            CASE A.ctype WHEN 'ctGIF' THEN GIFLINK.target ELSE NULL END gif_target_url,
             A.track_language, COALESCE(TL1.name,TL2.name)track_language_name,
            MCGL.genre_id genre_id, COALESCE(MCGS1.name,MCGS2.name) genre_name,
            MCO.country_id origin_country_id, COALESCE(MCOS1.name,MCOS2.name) origin_country_name,
            A.series_count,A.seasons_count,
            CASE  A.ctype WHEN 'ctTRAILER' THEN TRAILERLINK.target_url ELSE NULL END trailer_target_url,
            NULL as dmy
            FROM  media__content A 
            LEFT JOIN media__content__trailer TRAILERLINK ON (TRAILERLINK.id=A.id)
            LEFT JOIN media__content B0 ON (TRAILERLINK.content_id = B0.id)
            LEFT JOIN media__content__tag__list ATAG ON(ATAG.media_id=A.id AND ATAG.sort=0)
            LEFT JOIN media__content__tag__strings TAGSTR1 ON(TAGSTR1.id=ATAG.tag_id AND TAGSTR1.language_id='%s')
            LEFT JOIN media__content__tag__strings TAGSTR2 ON(TAGSTR2.id=ATAG.tag_id AND TAGSTR2.language_id='%s')
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=A.id)
            LEFT JOIN media__content__trailer__strings TRAILERSTRINGS1 ON(TRAILERSTRINGS1.id=A.id AND TRAILERSTRINGS1.language_id='%s')
            LEFT JOIN media__content__trailer__strings TRAILERSTRINGS2 ON(TRAILERSTRINGS2.id=A.id AND TRAILERSTRINGS2.language_id='%s')
            -- track language
            LEFT JOIN media__content__tracklang__strings TL1 ON(TL1.id=A.track_language AND TL1.language_id='%s')
            LEFT JOIN media__content__tracklang__strings TL2 ON(TL2.id=A.track_language AND TL2.language_id='%s')
            -- genre
            LEFT JOIN media__content__genre_list MCGL ON (MCGL.media_id=A.id AND MCGL.sort = 0)
            LEFT JOIN media__content__genre__strings MCGS1 ON(MCGS1.id=MCGL.genre_id AND MCGS1.language_id='%s')
            LEFT JOIN media__content__genre__strings MCGS2 ON(MCGS2.id=MCGL.genre_id AND MCGS2.language_id='%s')
            -- country
            LEFT JOIN media__content__origin MCO ON(MCO.id=A.id AND MCO.sort = 0)
            LEFT JOIN media__content__origin__country__strings MCOS1 ON(MCOS1.id=MCO.country_id AND MCOS1.language_id='%s')
            LEFT JOIN media__content__origin__country__strings MCOS2 ON(MCOS2.id=MCO.country_id AND MCOS2.language_id='%s')
            -- линки для gif
            LEFT JOIN media__content__gif GIFLINK ON(GIFLINK.id=A.id)
            LEFT JOIN media__content__gif__strings GIFSTRINGS1 ON(GIFSTRINGS1.id=GIFLINK.id AND GIFSTRINGS1.language_id='%s')
            LEFT JOIN media__content__gif__strings GIFSTRINGS2 ON(GIFSTRINGS2.id=GIFLINK.id AND GIFSTRINGS2.language_id='%s')            
            -- контентные линки для трейлера
            LEFT JOIN media__content__video VIDEOTRAILERLINK ON(VIDEOTRAILERLINK.id = TRAILERLINK.content_id) -- трейлер к фильму (фильм к трейлеру)
            LEFT JOIN media__content__season SOAPTRAILERLINK ON(SOAPTRAILERLINK.id = TRAILERLINK.content_id) -- трейлер к сериалу (сериал к трейлеру)
            LEFT JOIN media__content__season__season SEASONTRAILERLINK ON(SEASONTRAILERLINK.id=TRAILERLINK.content_id) -- трейлер к сезону (сезон к трейлеру)
            LEFT JOIN media__content__season SOAPSEASONTRAILERLINK ON(SOAPSEASONTRAILERLINK.id=SEASONTRAILERLINK.season_id) -- сериал для сезона к трейлеру
            -- -- языковые контентные линки для трейлера
            LEFT JOIN media__content__video__strings__lang_%s TRAILERVIDEOSTRINGS1 ON(TRAILERVIDEOSTRINGS1.id=TRAILERLINK.content_id) -- линк на строки видео для трейлера
            LEFT JOIN media__content__video__strings__lang_%s TRAILERVIDEOSTRINGS2 ON(TRAILERVIDEOSTRINGS2.id=TRAILERLINK.content_id) -- линк на строки видео для трейлера
            LEFT JOIN media__content__season__strings__lang_%s TRAILERSOAPSTRINGS1 ON(TRAILERSOAPSTRINGS1.id=TRAILERLINK.content_id) -- линк на сериал для трейлера
            LEFT JOIN media__content__season__strings__lang_%s TRAILERSOAPSTRINGS2 ON(TRAILERSOAPSTRINGS2.id=TRAILERLINK.content_id)-- линк на серал для трейлера
            LEFT JOIN media__content__seasonseason__strings__lang_%s TRAILERSEASONSTRINGS1 ON(TRAILERSEASONSTRINGS1.id=TRAILERLINK.content_id) -- линк на сезон для трейлера
            LEFT JOIN media__content__seasonseason__strings__lang_%s TRAILERSEASONSTRINGS2 ON(TRAILERSEASONSTRINGS2.id=TRAILERLINK.content_id) -- линк на сезон для трейлера
            LEFT JOIN media__content__season__strings__lang_%s TRAILERSEASONSOAPSTRINGS1 ON(TRAILERSEASONSOAPSTRINGS1.id=SOAPSEASONTRAILERLINK.id) -- линк на сериал для сезона трейлера
            LEFT JOIN media__content__season__strings__lang_%s TRAILERSEASONSOAPSTRINGS2 ON(TRAILERSEASONSOAPSTRINGS2.id=SOAPSEASONTRAILERLINK.id)-- линк на серал для сезона трейлера            
            WHERE A.enabled=1 AND (A.ctype='ctTRAILER' OR A.ctype='ctGIF')
            ORDER BY A.id DESC  
            ";
        return $this->load_query_to_result($query_t, $language, $default_language, static::GIFTRAILS_PER_PAGE, $offset);
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

    protected function load_seasons(\Language\LanguageItem $language, \Language\LanguageItem $default_language, int $page) {
        $offset = static::SEASONS_PER_PAGE * $page;
        $common_media_context = \Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_POSTERS;
        $query_t = "SELECT SQL_CALC_FOUND_ROWS        
            A.id,A.id content_id,A.ctype content_type,A.enabled,A.free,
            ATAG.tag_id, COALESCE(TAGSTR1.name,TAGSTR2.name) tag_name,
             COALESCE(SOAPSTRINGS1.name,SOAPSTRINGS2.name) name,
            '{$common_media_context}' image_context,
            A.id image_owner,
            SOAPLINK.default_poster image,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars,
            A.track_language, COALESCE(TL1.name,TL2.name)track_language_name,
            MCGL.genre_id genre_id, COALESCE(MCGS1.name,MCGS2.name) genre_name,
            MCO.country_id origin_country_id, COALESCE(MCOS1.name,MCOS2.name) origin_country_name,
            A.series_count,A.seasons_count,
            NULL as dmy
            FROM media__content A 
            -- tag props
            LEFT JOIN media__content__tag__list ATAG ON(ATAG.media_id=A.id AND ATAG.sort=0)
            LEFT JOIN media__content__tag__strings TAGSTR1 ON(TAGSTR1.id=ATAG.tag_id AND TAGSTR1.language_id='%s')
            LEFT JOIN media__content__tag__strings TAGSTR2 ON(TAGSTR2.id=ATAG.tag_id AND TAGSTR2.language_id='%s')
            -- track language
            LEFT JOIN media__content__tracklang__strings TL1 ON(TL1.id=A.track_language AND TL1.language_id='%s')
            LEFT JOIN media__content__tracklang__strings TL2 ON(TL2.id=A.track_language AND TL2.language_id='%s')
            -- genre
            LEFT JOIN media__content__genre_list MCGL ON (MCGL.media_id=A.id AND MCGL.sort = 0)
            LEFT JOIN media__content__genre__strings MCGS1 ON(MCGS1.id=MCGL.genre_id AND MCGS1.language_id='%s')
            LEFT JOIN media__content__genre__strings MCGS2 ON(MCGS2.id=MCGL.genre_id AND MCGS2.language_id='%s')
            -- country
            LEFT JOIN media__content__origin MCO ON(MCO.id=A.id AND MCO.sort = 0)
            LEFT JOIN media__content__origin__country__strings MCOS1 ON(MCOS1.id=MCO.country_id AND MCOS1.language_id='%s')
            LEFT JOIN media__content__origin__country__strings MCOS2 ON(MCOS2.id=MCO.country_id AND MCOS2.language_id='%s')
            -- stars
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=A.id)
            LEFT JOIN media__content__season SOAPLINK ON(SOAPLINK.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s SOAPSTRINGS1 ON(SOAPSTRINGS1.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s SOAPSTRINGS2 ON(SOAPSTRINGS2.id=A.id)
            WHERE A.enabled = 1 AND A.ctype='ctSEASON'
            ORDER BY A.id DESC             
            ";
        return $this->load_query_to_result($query_t, $language, $default_language, static::SEASONS_PER_PAGE, $offset);
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
