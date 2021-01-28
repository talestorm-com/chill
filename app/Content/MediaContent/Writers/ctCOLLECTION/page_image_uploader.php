<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctCOLLECTION;

/**
 * Description of lent_image_uploder
 *
 * @author eve
 */
class page_image_uploader {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static ();
    }

    public function run(Writer $writer) {
        $files = \DataMap\FileMap::F()->get_by_field_name('poster_image2');
        if (count($files)) {
            if (!\ImageFly\MediaContextInfo::F()->context_exists('lent_poster')) {
                \ImageFly\MediaContextInfo::register_media_context('lent_poster', 1600, 1600, 100, 100);
            }
            $file = $files[0];
            \ImageFly\ImageFly::F()->process_upload_manual('lent_poster', $writer->result_id, md5('poster2'), $file);
            if (\ImageFly\ImageFly::F()->image_exists('lent_poster', $writer->result_id, md5('poster2'))) {
                \DB\SQLTools\SQLBuilder::F()->push("UPDATE media__lent__mode_page SET lent_image_name2 =:Pn WHERE id=:Pi;")
                        ->push_params([
                            ":Pi" => $writer->result_id,
                            ":Pn" => md5('poster2'),
                        ])->execute_transact();
            }
        }
    }

}
