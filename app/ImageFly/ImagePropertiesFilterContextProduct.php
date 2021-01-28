<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

class ImagePropertiesFilterContextProduct extends DefaultPropertiesFilterDelegate {

    protected function get_default_filters() {
        return [
            'color' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
