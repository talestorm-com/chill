<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Stickers;

/**
 * Description of Lister
 *
 * @author eve
 */
class Lister extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return[
        ];
    }

    protected function get_sort_separator(): string {
        return '';
    }

    protected function get_sorts(): array {
        return [];
    }

    protected function build_query() {
        $t = "SELECT * FROM chill__review__sticker %s %s %s %s;";
        return sprintf($t, $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
