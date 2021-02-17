<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video\Writer;

/**
 * Description of QtyWriter
 *
 * @author eve
 */
class QtyWriter {

    public function run(VideoGroupWriter $w) {
        \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO video__group__qty (id,qty) VALUES(:P,COALESCE(( SELECT COUNT(*) FROM video__group__item WHERE id=:P ),0)) ON DUPLICATE KEY UPDATE qty=VALUES(qty);")
                ->push_param(":P", $w->result_id)->execute();
    }

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \Content\Video\Writer\QtyWriter
     */
    public static function F(): QtyWriter {
        return new static();
    }

}
