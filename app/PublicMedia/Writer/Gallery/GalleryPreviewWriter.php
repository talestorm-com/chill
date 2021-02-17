<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Gallery;

/**
 * Description of GalleryPreviewWriter
 * postwriter - uploader
 * @author eve
 */
class GalleryPreviewWriter {

    //put your code here
    public function __construct() {
        ;
    }

    /**
     * 
     * 
     * @return \PublicMedia\Writer\Gallery\GalleryPreviewWriter
     */
    public static function F(): GalleryPreviewWriter {
        return new static();
    }

    public function run(Writer $w) {
        $files = \DataMap\FileMap::F()->get_by_field_name("cover");
        if (count($files)) {
            foreach ($files as $file /* @var $file \DataMap\UploadedFile */) {
                if ($file->valid && preg_match("/^image/i", $file->type)) {
                    try {
                        Uploader::F()->upload_gallery_preview($file, $w->medial_object);
                        break;
                    } catch (\Throwable $e) {
                        $w->append_message($e->getMessage());
                    }
                }
            }
        }
    }

}
