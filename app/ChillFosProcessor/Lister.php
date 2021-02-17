<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ChillFosProcessor;

/**
 * Description of Lister
 *
 * @author eve
 */
class Lister extends \Content\Lister\Lister {

    protected function get_sort_separator(): string {
        return "|";
    }

    protected function get_filters(): array {
        return [
            'id' => 'Int:id',
            'contact' => 'String:contact',
            'email' => 'String:email',
            'name' => 'String:name',
            'common_name' => 'String:common_name',
            'ss_qty' => 'String:ss_qty',
        ];
    }

    protected function get_sorts(): array {
        return [
            'id' => 'id',
            'contact' => 'contact|id',
            'email' => 'email|id',
            'name' => 'name|id',
            'common_name' => 'common_name|id',
            'ss_qty' => 'ss_qty|id',
        ];
    }

    protected function build_query() {
        $t = "SELECT SQL_CALC_FOUND_ROWS id,contact,email,name,common_name,ss_qty FROM
            media_new_request
           %s %s %s %s
            
            ";
        return sprintf($t, $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
