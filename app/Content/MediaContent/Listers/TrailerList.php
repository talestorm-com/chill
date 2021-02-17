<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Content\MediaContent\Listers;
/**
 * Description of TrailerList
 *
 * @author eve
 */
class TrailerList extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'content_id' => 'Int:A.content_id',
            'name' => 'String:COALESCE(B.name,C.name)',
            'enabled' => 'Int:A0.enabled'
        ];
    }

    protected function get_sort_separator(): string {
        return "|";
    }

    protected function get_sorts(): array {
        return [
            'id' => 'A.id',
            'name' => 'COALESCE(B.name,C.name)|A.id',
            'enabled' => 'A0.enabled|A.id',
            'sort' => 'A.sort|A.id',
        ];
    }

    protected function build_query() {
        $lang = \Language\LanguageList::F()->get_current_language();
        $def_lang = \Language\LanguageList::F()->get_default_language();
        return sprintf("
            SELECT SQL_CALC_FOUND_ROWS A.id,A0.enabled,A.vertical,A.default_image,A.sort,COALESCE(B.name,C.name) name
            FROM media__content__trailer A 
            JOIN media__content A0 ON(A.id=A0.id)
            LEFT JOIN media__content__trailer__strings B ON(A.id=B.id AND B.language_id='%s')
            LEFT JOIN media__content__trailer__strings C ON(A.id=C.id AND C.language_id='%s')
            %s %s %s %s ;            
            ", $lang, $def_lang, $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
