<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront\MediaContentFrontSOAP;

/**
 * Description of SeriesList
 *
 * @author eve
 * @property int $season_id
 * @property SeriesListItem[] $items
 */
class SeriesList implements \common_accessors\IMarshall, \Iterator, \Countable {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var int */
    protected $season_id;

    /** @var SeriesListItem[] */
    protected $items;

    public function __construct() {
        $this->items = [];
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    /**
     * 
     * @param int $season_id
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return $this
     */
    public function load(int $season_id, \Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null) {
        $language ? 0 : $language = \Language\LanguageList::F()->get_current_language();
        $default_language ?: $default_language = \Language\LanguageList::F()->get_default_language();
        $this->season_id = $season_id;
        $query = "SELECT 
            A.id,B.seasonseason_id season_id,B.num,B.common_name,B.default_poster,B.vertical,B.default_preview,
            COALESCE(S1.name,S2.name) name,
            COALESCE(S1.intro,S2.intro) intro,
            COALESCE(S1.info,S2.info) info,
            COALESCE(P.price,0) price
            FROM media__content A
            JOIN media__content__season__series B ON(B.id=A.id)
            LEFT JOIN media__content_seasonseries_strings__lang_%s S1 ON(S1.id=A.id)
            LEFT JOIN media__content_seasonseries_strings__lang_%s S2 ON(S2.id=A.id)
            LEFT JOIN media__content__price P ON(P.id=A.id)
            WHERE B.seasonseason_id=:P AND A.enabled=1
            ORDER BY B.num,A.id;
            ";
        $rows = \DB\DB::F()->queryAll(sprintf($query, $language, $default_language), [":P" => $this->season_id]);
        foreach ($rows as $row) {
            try {
                $item = SeriesListItem::F($row);
                $this->items[] = $item;
            } catch (\Throwable $e) {                
            }
        }
        return $this;
    }
    
    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

}
