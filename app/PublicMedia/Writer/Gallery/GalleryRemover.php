<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Gallery;

/**
 * Description of GalleryRemover
 *
 * @author eve
 */
class GalleryRemover {    

    /** @var \PublicMedia\PublicMediaGallery */
    protected $gallery = null;

    public function __construct(\PublicMedia\PublicMediaGallery $gallery) {
        $this->gallery = $gallery;
    }

    public function run() {
        $this->gallery->destroy_cache();
        $dir = $this->gallery->get_files_path();
        \Helpers\Helpers::rm_dir_recursive($dir);
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM public__gallery WHERE id=:P")->push_param(":P", $this->gallery->id)->execute_transact();
    }

    public static function F(\PublicMedia\PublicMediaGallery $gallery) {
        return new static($gallery);
    }

}
