<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentRibbon;

/**
 * Description of MediaContentRibbonAbstract
 * @property MediaContentRibbonItem[] $items
 * @author eve
 */
abstract class MediaContentRibbonAbstract extends \Content\Content {

    /** @var MediaContentRibbonItem[] */
    protected $items;

    protected function __get__items() {
        return $this->items;
    }

    protected function extends_params(array &$params) {
        
    }

    protected function items_loaded() {
        
    }

    protected function load(\Language\LanguageItem $language, int $offset, int $perpage, bool $display_disabled) {
        $default_language = \Language\LanguageList::F()->get_default_language();
        $trailer_media_context = \Content\MediaContent\Readers\Trailer\MediaContentObject::MEDIA_CONTEXT;
        $common_media_context = \Content\MediaContent\Readers\ctSEASONSERIES\MediaContentObject::MEDIA_CONTEXT_POSTERS;
        $where = $display_disabled ? "" : " WHERE B.enabled=1 ";
        $this->extend_where($where);
        $joins = $this->extend_joins();
        $query = "
            SELECT A.id,A.content_id,B.ctype content_type,B.enabled,
            ATAG.tag_id, COALESCE(TAGSTR1.name,TAGSTR2.name) tag_name,
            CASE B.ctype
               WHEN 'ctVIDEO' THEN COALESCE(VIDEOSTRINGS1.name,VIDEOSTRINGS2.name)
               WHEN 'ctTRAILER' THEN COALESCE(TRAILERSTRINGS1.name,TRAILERSTRINGS2.name)
               WHEN 'ctSEASON' THEN COALESCE(SOAPSTRINGS1.name,SOAPSTRINGS2.name)
               WHEN 'ctSEASONSEASON' THEN COALESCE(SEASONSTRINGS1.name,SEASONSTRINGS2.name)
               WHEN 'ctSEASONSERIES' THEN COALESCE(SERIESSTRINGS1.name,SERIESSTRINGS2.name)
               WHEN 'ctBANNER' THEN 'banner'
               WHEN 'ctCOLLECTION' THEN COALESCE(COLLECTIONSTRINGS1.name,COLLECTIONSTRINGS2.name)
               WHEN 'ctGIF' THEN COALESCE(GIFSTRINGS1.name,GIFSTRINGS2.name)
               WHEN 'ctTEXT' THEN COALESCE(TXSTRINGS1.name,TXSTRINGS2.name)
            END  name,
            CASE B.ctype
               WHEN 'ctTRAILER' THEN  '{$trailer_media_context}'
               ELSE '{$common_media_context}'               
            END image_context,
            A.content_id image_owner,
            CASE B.ctype
               WHEN 'ctTRAILER' THEN TRAILERLINK.default_image
               WHEN 'ctVIDEO' THEN VIDEOLINK.default_poster
               WHEN 'ctSEASON' THEN SOAPLINK.default_poster
               WHEN 'ctSEASONSEASON' THEN SEASONLINK.default_poster
               WHEN 'ctSEASONSERIES' THEN SERIESLINK.default_poster
               WHEN 'ctBANNER' THEN BANNERLINK.default_poster
               WHEN 'ctCOLLECTION' THEN COLLECTIONLINK.default_poster
               WHEN 'ctGIF' THEN GIFLINK.default_poster
               WHEN 'ctTEXT' THEN TXLINK.default_poster
            END image,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars,
            -- выборка характеристик трейлера
            CASE B.ctype WHEN 'ctTRAILER' THEN B0.id  ELSE NULL END trailed_content, 
            CASE B.ctype WHEN 'ctTRAILER' THEN B0.ctype  ELSE NULL END trailed_content_type, -- на какой тип контента указывает трейлер
            CASE B.ctype WHEN 'ctTRAILER' THEN COALESCE(TRAILERVIDEOSTRINGS1.name,TRAILERVIDEOSTRINGS2.name) ELSE NULL END trailed_video_name, -- наименование для видео на которое указывает трейлер
            CASE B.ctype WHEN 'ctTRAILER' THEN COALESCE( TRAILERSOAPSTRINGS1.name,TRAILERSOAPSTRINGS2.name ) ELSE NULL END trailed_soap_name, -- наименование сериала на котороый указывает трейлер
            CASE B.ctype WHEN 'ctTRAILER' THEN COALESCE(TRAILERSEASONSTRINGS1.name,TRAILERSEASONSTRINGS2.name) ELSE NULL END trailed_season_name, -- наименование сезона на который указывает трейлер
            CASE B.ctype WHEN 'ctTRAILER' THEN COALESCE( TRAILERSEASONSOAPSTRINGS1.name,TRAILERSEASONSOAPSTRINGS2.name) ELSE NULL END trailed_season_soap_name, -- наименование сериала для сезона на который указывает трейлер
            --  выборка характеристик сезона
            CASE B.ctype WHEN 'ctSEASONSEASON' THEN COALESCE(SEASON2SOAPSTRINGS1.name,SEASON2SOAPSTRINGS2.name) ELSE NULL END season_soap_name, -- наименование для сериала если линк к сезону
            CASE B.ctype WHEN 'ctSEASONSEASON' THEN COALESCE(SEASON2SOAPSTRINGS1.id,SEASON2SOAPSTRINGS2.id) ELSE NULL END season_soap_id, -- id для сериала если линк к сезону
            --  выбрка характеристик серии
            CASE B.ctype WHEN 'ctSEASONSERIES' THEN COALESCE(SERIES2SEASONSTRINGS1.name,SERIES2SEASONSTRINGS2.name ) ELSE NULL END series_season_name, -- наименование сезона если тип-серия
            CASE B.ctype WHEN 'ctSEASONSERIES' THEN COALESCE(SERIES2SOAPSTRINGS1.name,SERIES2SOAPSTRINGS2.name ) ELSE NULL END series_soap_name, -- наименование сериала если тип-серия
            CASE B.ctype WHEN 'ctSEASONSERIES' THEN COALESCE(SERIES2SEASONSTRINGS1.id,SERIES2SEASONSTRINGS2.id ) ELSE NULL END series_season_id, -- id сезона если тип-серия
            CASE B.ctype WHEN 'ctSEASONSERIES' THEN COALESCE(SERIES2SOAPSTRINGS1.id,SERIES2SOAPSTRINGS2.id ) ELSE NULL END series_soap_id, -- id сериала если тип-серия
            --  выборка характеристик баннера
            CASE B.ctype WHEN 'ctBANNER' THEN BANNERLINK.background ELSE NULL END banner_background_color,
            CASE B.ctype WHEN 'ctBANNER' THEN BANNERLINK.text_color ELSE NULL END banner_foreground_color,
            CASE B.ctype WHEN 'ctBANNER' THEN COALESCE(BANNERSTRINGS1.url,BANNERSTRINGS2.url) ELSE NULL END banner_url,
            CASE B.ctype WHEN 'ctBANNER' THEN COALESCE(BANNERSTRINGS1.bannertext,BANNERSTRINGS2.bannertext) ELSE NULL END banner_text,   
            -- выборка характеристик GIF
            CASE B.ctype WHEN 'ctGIF' THEN GIFLINK.cdn_url ELSE NULL END gif_cdn_url,
            CASE B.ctype WHEN 'ctGIF' THEN GIFLINK.target ELSE NULL END gif_target_url,
            -- выборка характеристик TEXT
            CASE B.ctype WHEN 'ctTEXT' THEN COALESCE(TXSTRINGS1.intro,TXSTRINGS2.intro) ELSE NULL END text_short_text,
            CASE B.ctype WHEN 'ctTEXT' THEN TXLINK.post ELSE NULL END news_post,
            NULL as dmy
            FROM media__lent A JOIN media__content B ON(A.content_id=B.id)            
            LEFT JOIN media__content__trailer TRAILERLINK ON (TRAILERLINK.id=B.id)
            LEFT JOIN media__content B0 ON (TRAILERLINK.content_id = B0.id)
            LEFT JOIN media__content__tag__list ATAG ON(ATAG.media_id=B.id AND ATAG.sort=0)
            LEFT JOIN media__content__tag__strings TAGSTR1 ON(TAGSTR1.id=ATAG.tag_id AND TAGSTR1.language_id='%s')
            LEFT JOIN media__content__tag__strings TAGSTR2 ON(TAGSTR2.id=ATAG.tag_id AND TAGSTR2.language_id='%s')
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=B.id)
            LEFT JOIN media__content__video VIDEOLINK ON(VIDEOLINK.id=B.id)
            LEFT JOIN media__content__season SOAPLINK ON(SOAPLINK.id=B.id)
            LEFT JOIN media__content__season__season SEASONLINK ON(SEASONLINK.id=B.id)
            LEFT JOIN media__content__season__series SERIESLINK ON(SERIESLINK.id=B.id)
            LEFT JOIN media__content__video__strings__lang_%s VIDEOSTRINGS1 ON(VIDEOSTRINGS1.id=B.id)
            LEFT JOIN media__content__video__strings__lang_%s VIDEOSTRINGS2 ON(VIDEOSTRINGS2.id=B.id)
            LEFT JOIN media__content__trailer__strings TRAILERSTRINGS1 ON(TRAILERSTRINGS1.id=B.id AND TRAILERSTRINGS1.language_id='%s')
            LEFT JOIN media__content__trailer__strings TRAILERSTRINGS2 ON(TRAILERSTRINGS2.id=B.id AND TRAILERSTRINGS2.language_id='%s')
            LEFT JOIN media__content__season__strings__lang_%s SOAPSTRINGS1 ON(SOAPSTRINGS1.id=B.id)
            LEFT JOIN media__content__season__strings__lang_%s SOAPSTRINGS2 ON(SOAPSTRINGS2.id=B.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s SEASONSTRINGS1 ON(SEASONSTRINGS1.id=B.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s SEASONSTRINGS2 ON(SEASONSTRINGS2.id=B.id)
            LEFT JOIN media__content_seasonseries_strings__lang_%s SERIESSTRINGS1 ON(SERIESSTRINGS1.id=B.id)
            LEFT JOIN media__content_seasonseries_strings__lang_%s SERIESSTRINGS2 ON(SERIESSTRINGS2.id=B.id) 
            LEFT JOIN media__content__banner BANNERLINK ON(BANNERLINK.id=B.id)
            LEFT JOIN media__content__banner__strings BANNERSTRINGS1 ON(BANNERSTRINGS1.id=BANNERLINK.id AND BANNERSTRINGS1.language_id='%s')
            LEFT JOIN media__content__banner__strings BANNERSTRINGS2 ON(BANNERSTRINGS2.id=BANNERLINK.id AND BANNERSTRINGS2.language_id='%s')
            -- линки для коллекции
            LEFT JOIN media__content__collection COLLECTIONLINK ON(COLLECTIONLINK.id=B.id)
            LEFT JOIN media__content__collection_strings_lang_%s COLLECTIONSTRINGS1 ON(COLLECTIONSTRINGS1.id=COLLECTIONLINK.id )
            LEFT JOIN media__content__collection_strings_lang_%s COLLECTIONSTRINGS2 ON(COLLECTIONSTRINGS2.id=COLLECTIONLINK.id )
            -- линки для gif
            LEFT JOIN media__content__gif GIFLINK ON(GIFLINK.id=B.id)
            LEFT JOIN media__content__gif__strings GIFSTRINGS1 ON(GIFSTRINGS1.id=GIFLINK.id AND GIFSTRINGS1.language_id='%s')
            LEFT JOIN media__content__gif__strings GIFSTRINGS2 ON(GIFSTRINGS2.id=GIFLINK.id AND GIFSTRINGS2.language_id='%s')            
            -- линки для txt
            LEFT JOIN media__content__text TXLINK ON(TXLINK.id=B.id)
            LEFT JOIN media__content__text__strings__lang_%s TXSTRINGS1 ON(TXSTRINGS1.id=TXLINK.id )
            LEFT JOIN media__content__text__strings__lang_%s TXSTRINGS2 ON(TXSTRINGS2.id=TXLINK.id )
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
            --  контентные линки для серии
            LEFT JOIN media__content__season__season SERIES2SEASONLINK ON(SERIES2SEASONLINK.id=SERIESLINK.seasonseason_id) -- сезон к серии
            LEFT JOIN media__content__season SERIES2SOAPLINK ON(SERIES2SOAPLINK.id=SERIES2SEASONLINK.season_id) -- сериал к сезону серии
            -- -- языковые контентные линки для серии
            LEFT JOIN media__content__seasonseason__strings__lang_%s SERIES2SEASONSTRINGS1 ON(SERIES2SEASONSTRINGS1.id=SERIES2SEASONLINK.id) -- строки для сезона для серии
            LEFT JOIN media__content__seasonseason__strings__lang_%s SERIES2SEASONSTRINGS2 ON(SERIES2SEASONSTRINGS2.id=SERIES2SEASONLINK.id) -- строки для сезона для серии
            LEFT JOIN media__content__season__strings__lang_%s SERIES2SOAPSTRINGS1 ON(SERIES2SOAPSTRINGS1.id=SERIES2SOAPLINK.id) -- строки для сериала для сезона для серии
            LEFT JOIN media__content__season__strings__lang_%s SERIES2SOAPSTRINGS2 ON(SERIES2SOAPSTRINGS2.id=SERIES2SOAPLINK.id) -- строки для сериала для сезона для серии
            -- контентные лики для сезона
            LEFT JOIN media__content__season SEASON2SOAPLINK ON(SEASON2SOAPLINK.id=SEASONLINK.season_id) -- сериал к сезону
            -- языковые контентные лики для серала сезона
            LEFT JOIN media__content__season__strings__lang_%s SEASON2SOAPSTRINGS1 ON(SEASON2SOAPSTRINGS1.id=SEASON2SOAPLINK.id) -- строки для сериала для сезона 
            LEFT JOIN media__content__season__strings__lang_%s SEASON2SOAPSTRINGS2 ON(SEASON2SOAPSTRINGS2.id=SEASON2SOAPLINK.id) -- строки для сериала для сезона 
             {$joins}  
               {$where}
            
            ORDER BY A.id DESC
            LIMIT {$perpage} OFFSET {$offset};
            ";
        $cc = mb_substr_count($query, "%s");
        $cap = [];
        while (count($cap) < $cc) {
            $cap[] = $language;
            $cap[] = $default_language;
        };
        $query_patams = [];
        $this->extends_params($query_patams);
//        if(\DataMap\InputDataMap::F()->get_filtered('debug_lent',['Boolean','DEfaultFalse'])){
//            var_dump($cap);die();
//        }

        $aquery = call_user_func_array('sprintf', array_merge([$query], $cap));
        $rows = \DB\DB::F()->queryAll($aquery, $query_patams);
        $this->items = [];
        foreach ($rows as $row) {
            try {
                $this->items[] = MediaContentRibbonItem::F($row);
            } catch (\Throwable $e) {
                
            }
        }
        $this->items_loaded();
    }

    protected function extend_where($where): string {
        return $where;
    }

    protected function extend_joins(): string {
        return "";
    }

    public static function reset_cache() {
        
    }

    public static function delete(int $id) {
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM media__lent WHERE id=:P;")->push_param(":P", $id)->execute_transact();
        static::reset_cache();
    }

    public static function prepend(int $id) {
        \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO media__lent(content_id)VALUES(:P);")->push_param(":P", $id)->execute_transact();
        static::reset_cache();
    }

    public static function prepend_array(array $id) {
        $i = [];
        $c = 0;
        $p = [];
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->inc_counter();
        foreach ($id as $intid) {
            $intid = \Filters\FilterManager::F()->apply_chain($intid, ['IntMore0', 'DefaultNull']);
            if ($intid) {
                $i[] = "(:P{$b->c}_i{$c}_id)";
                $p[":P{$b->c}_i{$c}_id"] = $intid;
                $c++;
            }
        }
        if (count($i)) {
            $b->push(sprintf("INSERT INTO media__lent(content_id) VALUES %s;", implode(",", $i)))->push_params($p)->execute_transact();
            static::reset_cache();
        }
    }

}
