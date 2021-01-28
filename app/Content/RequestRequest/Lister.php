<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\RequestRequest;

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
        return ['id' => "Int:A.id",
            'name' => "String:B.name",
            'finished' => 'Int:A.finished',
            'company'=>'String:A.company_name',
            'profile'=>'String:A.profile_name',
            'status'=>'String:A.status_name',
            'created'=>'Date:A.created',
            'amount'=>'Int:A.position_cost',
            'total'=>'Int:A.position_cost+A.nds_eur'
            
        ];
        
    }

    /**
     * 
     * @return array
     */
    protected function get_sorts(): array {
        return [
            'id' => "A.id",
            'name' => "A.name|A.id",
            'finished' => "A.finised|A.id",
            'company'=>'A.company_name|A.id',
            'created' => "A.created|A.id",
            
        ];
    }

    protected function build_query() {
        return sprintf("SELECT SQL_CALC_FOUND_ROWS 
            A.id,
            A.company_name company,
            B.name,A.status_color,A.status_name, A.profile_name profile, A.position_cost amount,
            A.nds_pc,A.nds_eur,A.position_cost+A.nds_eur total,
            A.status_name,A.status_id,A.status_color,DATE_FORMAT(A.created,'%%d.%%m.%%Y %%H:%%i') created,A.finished
            FROM request A LEFT JOIN user U ON(A.user_id=U.id) LEFT JOIN user__fields B ON(B.id=U.id)
            %s %s %s %s",
                $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit
        );
    }

}
