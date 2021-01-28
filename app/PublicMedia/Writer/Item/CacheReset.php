<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Item;

/**
 * Description of TextWriter
 *
 * @author eve
 */
class CacheReset {

    public function __construct() {
        ;
    }

    /**
     * 
     * 
     * @return \PublicMedia\Writer\Item\CacheReset
     */
    public static function F(): CacheReset {
        return new static();
    }

    public function run(Writer $w) {
        $dep = sprintf(\PublicMedia\PublicMediaGallery::CACHE_BEAKON, $w->medial_object->uid);
        \Cache\FileBeaconDependency::F([$dep])->reset_dependency_beacons();
    }

}
