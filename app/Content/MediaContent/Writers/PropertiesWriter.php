<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers;

/**
 * Description of PropertiesWriter
 *
 * @author eve
 */
class PropertiesWriter {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(AWriter $writer) {
        $raw_data = \Filters\FilterManager::F()->apply_filter_datamap($writer->input, ["properties" => ['NEArray', 'DefaultEmptyArray']]);
        $props = \Content\MediaContent\Properties::F();
        $props->load_from_object_array($raw_data['properties']);
        $props->save($writer->builder, $writer->temp_var);
    }

}
