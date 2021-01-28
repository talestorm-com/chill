<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\RequestStatus;

/**
 * Description of Lister
 *
 * @author eve
 */
class Lister extends \Content\Lister\Lister {

    /**
     * 
     * @return array
     */
    protected function get_filters(): array {
        return ['id' => "A.id",
            'name' => "A.name",
            'final' => 'Int:A.final',
            'sort' => 'Int:A.sort'
        ];
    }

    /**
     * 
     * @return array
     */
    protected function get_sorts(): array {
        return [
            'id' => "A.id",
            'name' => "A.name|A.sort|A.id",
            'final' => "A.final|A.sort|A.name|A.id",
            'sort' => "A.sort|A.id",
        ];
    }

    protected function build_query() {
        return sprintf("SELECT SQL_CALC_FOUND_ROWS A.id,A.name,A.color,A.final,A.sort FROM request__status A %s %s %s %s",
                $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit
        );
    }

}
