<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Promo;

/**
 * Description of Lister
 *
 * @author eve
 */
class Lister extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'name' => 'String:A.name',
            'code' => 'String:A.code',
        ];
    }

    protected function get_sorts(): array {
        return [
            'id' => 'A.id',
            'name' => 'A.name|A.id',
            'code' => 'A.name|A.code',
        ];
    }

    protected function get_sort_separator(): string {
        return '|';
    }

    protected function build_query() {
        return sprintf("SELECT id,name,code FROM chill__promo A %s %s %s %s", $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
