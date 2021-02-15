<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MediaVendor;

/**
 * Description of Lister
 *
 * @author eve
 */
class Lister extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'common_name' => 'String:A.common_name',
            'name' => 'String:COALESCE(B.name,C.name)'
        ];
    }

    protected function get_sort_separator(): string {
        return "|";
    }

    protected function get_sorts(): array {
        return [
            'id' => 'A.id',
            'common_name' => 'A.common_name|A.id',
            'name' => 'COALESCE(B.name,C.name)|A.id',
        ];
    }

    protected function build_query() {
        return sprintf("
            SELECT SQL_CALC_FOUND_ROWS
            A.id,A.common_name,
            COALESCE(B.name,C.name) name
            FROM media__studio A
            LEFT JOIN media__studio__strings__lang_%s B ON(B.id=A.id)
            LEFT JOIN media__studio__strings__lang_%s C ON(C.id=A.id)
            %s %s %s %s
            ",
                \Language\LanguageList::F()->get_current_language(),
                \Language\LanguageList::F()->get_default_language(),
                $this->filter->whereWord,
                $this->where, $this->sort->SQL, $this->limit->MySqlLimit
        );
    }

}
