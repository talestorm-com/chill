<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile\Writer;

class PropertyWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \Content\CatalogTile\Writer\PropertyWriter
     */
    public static function F(): PropertyWriter {
        return new static();
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $props = \Content\CatalogTile\PropertyCollection::F();
        $props->load_from_object_array($input->get_filtered('properties', ['NEArray', 'DefaultEmptyArray']));
        $props->save($b, $var);
    }

}
