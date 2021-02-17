<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MediaTag;

/**
 * Description of Lister
 *
 * @author eve
 */
class Lister extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'name' => 'String:COALESCE(B.name,C.name)',
        ];
    }

    protected function get_sort_separator(): string {
        return "|";
    }

    protected function get_sorts(): array {
        return [
            'id' => 'A.id',
            'name' => 'COALESCE(B.name,C.name)|A.id',
        ];
    }

    protected function build_query() {
        return sprintf("SELECT SQL_CALC_FOUND_ROWS A.id,COALESCE(B.name,C.name) name
            FROM media__content__tag A 
            LEFT JOIN media__content__tag__strings B ON(A.id=B.id AND B.language_id='%s')
            LEFT JOIN media__content__tag__strings C ON(A.id=C.id AND C.language_id='%s')
            %s %s %s %s
            ",
                \Language\LanguageList::F()->get_current_language(),
                \Language\LanguageList::F()->get_default_language(),
                $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit
        );
    }

}
