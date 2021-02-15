<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MediaVendor;

/**
 * Description of MediaVendorRemover
 *
 * @author eve
 * @property int $id
 */
class MediaVendorRemover {

    //put your code here
    protected $id;

    public function __construct(int $id) {
        $this->id = $id;
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id) {
        return new static($id);
    }

    public function run() {
        \ImageFly\ImageFly::F()->remove_images(MediaVendor::MEDIA_CONTEXT, $this->id);
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM media__studio WHERE id=:P;")->push_param(":P", $this->id)->execute_transact();
        MediaVendor::reset_cached();
    }

}
