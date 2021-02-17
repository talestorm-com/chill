<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video\Writer;

/**
 * Description of ImagesWriter
 *
 * @author eve
 */
class ImagesWriter {

    //put your code here


    public function run(VideoGroupWriter $w) {
        $uploader = \ImageFly\FormImageUploader::F("video_common_image", \Content\Video\VideoGroup::MEDIA_CONTEXT, $w->result_id);
        $uploader->run();
        if (count($uploader->log->get_messages())) {
            \Errors\common_error::R(implode("\n", $uploader->log->get_messages()));
        }
    }

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \Content\Video\Writer\ImagesWriter
     */
    public static function F(): ImagesWriter {
        return new static();
    }

}
