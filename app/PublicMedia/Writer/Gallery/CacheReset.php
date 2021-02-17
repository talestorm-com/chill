<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Gallery;

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
     * @return \PublicMedia\Writer\Gallery\CacheReset
     */
    public static function F(): CacheReset {
        return new static();
    }

    public function run(Writer $w) {
        \PublicMedia\PublicMediaGallery::reset_cache_for($w->result_id);        
    }

}
