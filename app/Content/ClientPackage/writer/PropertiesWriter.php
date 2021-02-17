<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ClientPackage\writer;

/**
 * Description of PropertiesWriter
 *
 * @author eve
 */
class PropertiesWriter {

    public function run(writer $w) {
        $props = \Content\ClientPackage\Properies::F();
        $props->load_from_object_array($w->data_input->get_filtered("properties", ["NEArray", "DefaultEmptyArray"]));
        $props->save($w->builder, $w->temp_var);
        $w->builder->inc_counter();
    }
    
    
    public function __construct() {
        ;
    }
    
    /**
     * 
     * @return \Content\ClientPackage\writer\PropertiesWriter
     */
    public static function F():PropertiesWriter{
        return new static();
    }

}
