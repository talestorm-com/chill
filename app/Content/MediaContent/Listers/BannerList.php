<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Listers;

/**
 * Description of BannerList
 *
 * @author eve
 */
class BannerList extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'name' => 'String:B.name',
        ];
    }

    protected function get_sort_separator(): string {
        return '|';
    }

    protected function get_sorts(): array {
        return [
            'id' => 'A.id',
            'name' => 'B.name|A.id',
        ];
    }

    protected function build_query() {
        return sprintf(" SELECT SQL_CALC_FOUND_ROWS A.id,B.name FROM
            media__content__banner B JOIN media__content A ON(A.id=B.id)
            %s %s %s %s
            ", $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
