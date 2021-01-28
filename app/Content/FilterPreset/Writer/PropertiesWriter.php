<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\FilterPreset\Writer;

/**
 * Description of PropertiesWriter
 *
 * @author eve
 */
class PropertiesWriter {

    public function run(FilterPresetWriter $w) {
        $props = \Content\FilterPreset\FilterPresetPropertyCollection::F();
        $props->load_from_object_array($w->data_input->get_filtered("properties", ["NEArray", "DefaultEmptyArray"]));
        $props->save($w->builder, $w->temp_var);
        $w->builder->inc_counter();
    }
    
    
    public function __construct() {
        ;
    }
    
    /**
     * 
     * @return \Content\FilterPreset\Writer\PropertiesWriter
     */
    public static function F():PropertiesWriter{
        return new static();
    }

}
