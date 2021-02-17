<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Training;

/**
 * Description of MyHistoryLister
 *
 * @author eve
 */
class MyHistoryLister extends \Content\Lister\Lister {

    protected function build_query() {
        return sprintf("SELECT  SQL_CALC_FOUND_ROWS  *, DATE_FORMAT(DATE_ADD(datum, INTERVAL MOD(start,86400) SECOND),'%%H:%%i') `time`,
            DATE_FORMAT(datum,'%%d.%%m.%%Y') date_fmt
            FROM fitness__trainer__buisy                        
            %s %s %s %s            
            ", $this->filter->whereWord, $this->where, " ORDER BY datum DESC, start DESC ", $this->limit->MySqlLimit);
    }
    
    protected function create_direct_conditions() {
        $this->params[":PXuser"] = \Auth\Auth::F()->get_id();        
        $this->params[":PXdate"] = (new \DateTime())->format('Y-m-d H:i:s');        
        $this->filter->addDirectCondition("(user_id=:PXuser)");
        $this->filter->addDirectCondition("(state=2 OR datum<:PXdate OR DATE_ADD(datum, INTERVAL MOD(start,86400) SECOND)<:PXdate)");        
    }

    protected function get_filters(): array {
        return [
           
        ];
    }

    protected function get_sorts(): array {
        return [           
        ];
    }

}
