<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video\Writer;

/**
 * Description of PropertiesWriter
 *
 * @author eve
 */
class PropertiesWriter {

    public function run(VideoGroupWriter $w) {
        $props = \Content\Video\VideoGroupPropertyCollection::F();
        $props->load_from_object_array($w->data_input->get_filtered("properties", ["NEArray", "DefaultEmptyArray"]));
        $props->save($w->builder, $w->temp_var);
        $w->builder->inc_counter();
    }

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \Content\Video\Writer\PropertiesWriter
     */
    public static function F(): PropertiesWriter {
        return new static();
    }

}
