<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ClientPackage;

/**
 * Description of Lister
 *
 * @author eve
 */
class Lister extends \Content\Lister\Lister {

    protected function build_query() {
        return sprintf("SELECT  SQL_CALC_FOUND_ROWS  id,name,price,days,usages,default_image,active
            
            FROM fitness__package
            %s %s %s %s            
            ", $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

    protected function get_filters(): array {
        return [
            'id' => 'Int:id',
            'name' => 'String:name',
            'price' => 'Numeric:price',
            'days' => 'Int:days',
            'active' => 'Int:active',
            'usages' => 'Int:usages',
        ];
    }

    protected function get_sorts(): array {
        return [
            'id' => 'id',
            'name' => 'name|id',
            'price' => 'price|id',
            'days' => 'days|id',
            'usages' => 'usages|id',
        ];
    }

}
