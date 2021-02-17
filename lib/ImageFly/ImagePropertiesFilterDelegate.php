<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

interface ImagePropertiesFilterDelegate {

    const MODE_READ = "read";
    const MODE_SET = "post";
    const MODE_APPEND = "append";

    public function get_filters(string $mode = null): array;

    public function get_filters_params(string $mode = null);

    public static function F(): ImagePropertiesFilterDelegate;
}
