<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Writer;

class CrossWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \DataModel\Product\Writer\CrossWriter
     */
    public static function F(): CrossWriter {
        return new static();
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $crosses = $input->get_filtered("cross", ['NEArray', 'DefaultEmptyArray']);
        $clean_cross = [];
        foreach ($crosses as $cross) {
            $cross = intval($cross);
            if($cross && $cross>0){
                $clean_cross[]=$cross;
            }            
        }
        $clean_cross = array_unique($clean_cross);
        $b->inc_counter();
        $b->push("DELETE FROM catalog__product__product__link WHERE product_1={$var};");
        $inserts = [];
        $ic = 0;
        $params = [];
        foreach ($clean_cross as $cross) {
            $inserts[] = "({$var},:P{$b->c}_{$ic}_p2)";
            $params[":P{$b->c}_{$ic}_p2"] = $cross;            
            $ic++;
        }
        if (count($inserts)) {
            $b->push(sprintf("INSERT INTO catalog__product__product__link(product_1,product_2) VALUES %s ON DUPLICATE KEY UPDATE product_2=VALUES(product_2);", implode(",", $inserts)));
            $b->push_params($params);
        }
        $b->inc_counter();
    }
    

}
