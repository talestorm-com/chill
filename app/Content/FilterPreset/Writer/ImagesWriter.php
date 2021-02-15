<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\FilterPreset\Writer;

/**
 * Description of ImagesWriter
 *
 * @author eve
 */
class ImagesWriter {

    //put your code here


    public function run(FilterPresetWriter $w) {
        $uploader = \ImageFly\FormImageUploader::F("filter_common_image", \Content\FilterPreset\FilterPreset::MEDIA_CONTEXT, $w->result_id);
        $uploader->run();
        if (count($uploader->log->get_messages())) {
            \Errors\common_error::R(implode("\n", $uploader->log->get_messages()));
        }
    }

    public function __construct() {
        ;
    }

    public static function F(): ImagesWriter {
        return new static();
    }

}
