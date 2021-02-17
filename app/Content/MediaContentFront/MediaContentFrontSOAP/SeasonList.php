<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront\MediaContentFrontSOAP;

/**
 * Description of SeasonList
 *
 * @author eve
 * @property int $soap_id
 * @property SeasonListItem[] $items
 */
class SeasonList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $soap_id;

    /** @var SeasonListItem[] */
    protected $items;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__soap_id() {
        return $this->soap_id;
    }

    /** @return SeasonListItem[] */
    protected function __get__items() {
        return $this->items;
    }

    //</editor-fold>


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
     * @param int $soap_id
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return $this
     */
    public function load(int $soap_id, \Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null) {
        $language ? 0 : $language = \Language\LanguageList::F()->get_current_language();
        $default_language ? 0 : $default_language = \Language\LanguageList::F()->get_default_language();
        $this->soap_id = $soap_id;
        $query = "
            SELECT 
            A.id,
            B.season_id soap_id,
            B.common_name,
            B.num,
            B.default_poster,
            COALESCE(S1.name,S2.name) name,
            COALESCE(S1.intro,S2.intro) intro,
            COALESCE(S1.info,S2.info) info            
            FROM media__content A 
            JOIN media__content__season__season B ON(B.id=A.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s S1 ON(S1.id=A.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s S2 ON(S2.id=A.id)
            WHERE B.season_id=:P AND A.enabled=1
            ORDER BY B.num,B.id            
            ";
        $rq = sprintf($query, $language, $default_language);
        $rows = \DB\DB::F()->queryAll($rq, [":P" => $soap_id]);
        
        foreach ($rows as $row) {
            try {
                $this->items[] = SeasonListItem::F($row, $language, $default_language);
            } catch (\Throwable $e) {
                var_dump($e);die();
            }
        }
        /*
         * 'id' => ['IntMore0'], //int
          'soap_id' => ['IntMore0'], //int
          'common_name' => ['Strip', 'Trim', 'NEString'], //string
          'name' => ['Strip', 'Trim', 'NEString'], //string
          'num' => ['IntMore0', 'Default0'], //int
          'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
          'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
          'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
         */
        return $this;
    }
    
    
    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

}
