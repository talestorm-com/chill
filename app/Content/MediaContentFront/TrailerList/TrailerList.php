<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront\TrailerList;

/**
 * @property TrailerListItem[] $items
 * @property int $parent_id
 */
class TrailerList implements \common_accessors\IMarshall, \Iterator, \Countable {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var TrailerListItem[] */
    protected $items;

    /** @var int */
    protected $parent_id;

    /** @return TrailerListItem[] */
    protected function __get__items() {
        return $this->items;
    }

    /** @return int */
    protected function __get__parent_id() {
        return $this->parent_id;
    }

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
     * @param int $id
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return $this
     */
    public function load(int $id, \Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null) {
        $language ? 0 : $language = \Language\LanguageList::F()->get_current_language();
        $default_language ? 0 : $default_language = \Language\LanguageList::F()->get_default_language();
        $this->parent_id = $id;
        $query = "
             SELECT A.id,A.enabled,B.vertical,B.default_image,COALESCE(S1.name,S2.name) name             
            FROM media__content__trailer B
            JOIN media__content A ON(A.id=B.id)
            LEFT JOIN media__content__trailer__strings S1 ON(A.id=S1.id AND S1.language_id='%s')
            LEFT JOIN media__content__trailer__strings S2 ON(A.id=S2.id AND S2.language_id='%s')
            WHERE B.content_id=:P
            ORDER BY B.sort,B.id
            ";
        $q = sprintf($query, $language, $default_language);
        $rows = \DB\DB::F()->queryAll($q, [':P' => $this->parent_id]);
        foreach ($rows as $row) {
            try {
                $item = TrailerListItem::F($row);
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
