<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Item;

/**
 * Description of TextWriter
 *
 * @author eve
 */
class QtyWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * 
     * @return \PublicMedia\Writer\Item\QtyWriter
     */
    public static function F(): QtyWriter {
        return new static();
    }

    public function run(Writer $w) {
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("INSERT INTO public__gallery__counter (id,qty) VALUES( :P{$b->c}id,COALESCE( (SELECT COUNT(*) FROM public__gallery__item WHERE gallery_id=:P{$b->c}id ) ,0) ) ON DUPLICATE KEY UPDATE public__gallery__counter.qty=VALUES(qty);")
                ->push_param(":P{$b->c}id", $w->medial_object->id)->execute();
    }

}
