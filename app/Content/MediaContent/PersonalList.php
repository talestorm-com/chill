<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent;

/**
 * Description of PersonalList
 *
 * @author eve
 * @property PersonalListItem[]
 */
class PersonalList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    protected $items;

    protected function __get__items() {
        return $this->items;
    }

    public function __construct() {
        $this->items = [];
    }

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function load(int $id, \Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null) {
        $language ? 0 : $language = \Language\LanguageList::F()->get_current_language();
        $default_language ? 0 : $default_language = \Language\LanguageList::F()->get_default_language();
        $query = "SELECT A.person_id,A.value,A.sort,B.common_name,COALESCE(C.name,D.name) name
            FROM media__content__personal A 
            JOIN media__content__actor B ON(A.person_id=B.id)
            LEFT JOIN media__content__actor__strings_lang_%s C ON(C.id=B.id)
            LEFT JOIN media__content__actor__strings_lang_%s D ON(D.id=B.id)
            WHERE A.content_id=:P 
            ORDER BY A.sort,A.value
            ";
        $rows = \DB\DB::F()->queryAll(sprintf($query, $language, $default_language), [":P" => $id]);
        foreach ($rows as $row) {
            try {
                $item = PersonalListItem::F($row);
                $this->items[] = $item;
            } catch (\Throwable $e) {
                
            }
        }
        return $this;
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    /**
     * 
     * @param string $role
     * @return PersonalListItem[]
     */
    public function filter(string $role) {
        $r = [];
        foreach ($this->items as $item) {
            if ($item->value === $role) {
                $r[] = $item;
            }
        }
        return $r;
    }

}
