<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of CountryLister
 *
 * @author eve
 */
class CountryLister extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'name_common_name' => 'String:A.common_name',
            'name_name' => 'String:COALESCE(B.name,C.name)'
        ];
    }

    protected function get_sort_separator(): string {
        return "|";
    }

    protected function get_sorts(): array {
        return [
            'id' => 'A.id',
            'name_common_name' => 'A.common_name|A.id',
            'name_name' => 'COALESCE(B.name,C.name)|A.id',
        ];
    }

    protected function build_query() {
        $this->params[":Plang"] = LanguageList::F()->get_current_language();
        $this->params[":Pdeflang"] = LanguageList::F()->get_default_language();
        return sprintf("SELECT SQL_CALC_FOUND_ROWS A.id,A.common_name, COALESCE(B.name,C.name) name FROM media__content__origin_country A
            LEFT JOIN media__content__origin__country__strings B ON (B.id=A.id AND B.language_id=:Plang)
            LEFT JOIN media__content__origin__country__strings C ON (C.id=A.id AND C.language_id=:Pdeflang)
            %s %s %s %s
            ", $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
