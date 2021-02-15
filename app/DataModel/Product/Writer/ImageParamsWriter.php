<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Writer;

class ImageParamsWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \DataModel\Product\Writer\ImageParamsWriter
     */
    public static function F(): ImageParamsWriter {
        return new static();
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, int $product_id) {
        
        if ($input->exists('images')) {            
            $images = $input->get_filtered('images', ['NEArray', 'DefaultEmptyArray']);
            $cli_images = [];
            foreach ($images as $image_key => $image_props) {
                if (preg_match("/^[0-9a-f]{32}$/i", $image_key) && is_array($image_props)) {
                    $cli_images[$image_key] = $image_props;
                }
            }
            
            \ImageFly\ImageFly::F()->set_images_properties(\ImageFly\IMediaContext::PRODUCT, $product_id, $cli_images);
        }
    }

    protected function get_filters() {
        return [
            'title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'description' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'keywords' => ['Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

}
