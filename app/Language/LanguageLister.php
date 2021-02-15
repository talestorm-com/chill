<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of LanguageLister
 *
 * @author eve
 */
class LanguageLister extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'String:id',
            'name_en' => 'String:name_en',
            'name' => 'String:name',
            'sort' => 'Int:sort',
            'enabled' => 'Int:enabled',
        ];
    }

    protected function get_sorts(): array {
        return [
            'id' => 'id',
            'name_en' => 'name_en|id',
            'name' => 'name|id',
            'sort' => 'sort|id',
            'enabled' => 'enabled|id',
        ];
    }

    protected function get_sort_separator(): string {
        return '|';
    }

    protected function build_query() {
        return sprintf("SELECT SQL_CALC_FOUND_ROWS id,name_en,name,enabled,sort FROM language__language %s %s %s %s", $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
