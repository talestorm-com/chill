<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\TrainingHall;

/**
 * Description of TrainingHallLister
 *
 * @author eve
 */
class TrainingHallLister extends \Content\Lister\Lister {

    protected function build_query() {
        return sprintf("SELECT  SQL_CALC_FOUND_ROWS  id,name,address,phone,default_image
            
            FROM fitness__places
            %s %s %s %s            
            ", $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

    protected function get_filters(): array {
        return [
            'id' => 'Int:id',
            'name' => 'String:name',
            'address' => 'String:address',
        ];
    }

    protected function get_sorts(): array {
        return [
            'id' => 'id',
            'name' => 'name|id',
        ];
    }

}
