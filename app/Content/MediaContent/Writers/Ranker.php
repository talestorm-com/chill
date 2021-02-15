<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers;

/**
 * Description of Ranker
 *
 * @author eve
 */
class Ranker {
    //put your code here

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(AWriter $writer) {

        $query = "SET @rownum = 0;
                UPDATE media__content
                SET mcsort = (@rownum := 1 + @rownum) * 10                
                ORDER BY mcsort,id DESC";
        \DB\SQLTools\SQLBuilder::F()->push($query)->execute();
    }

}
