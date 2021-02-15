<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\FilterPreset\Writer;

/**
 * Description of QtyWriter
 *
 * @author eve
 */
class QtyWriter {

    public function run(FilterPresetWriter $w) {
        \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO filterpreset__qty (id,qty) VALUES(:P,COALESCE(( SELECT COUNT(*) FROM filterpreset__item WHERE id=:P ),0)) ON DUPLICATE KEY UPDATE qty=VALUES(qty);")
                ->push_param(":P", $w->result_id)->execute();
    }

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \Content\FilterPreset\Writer\QtyWriter
     */
    public static function F(): QtyWriter {
        return new static();
    }

}
