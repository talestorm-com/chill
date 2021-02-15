<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctSEASON;

/**
 * Description of lent_image_uploder
 *
 * @author eve
 */
class lent_image_uploder {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static ();
    }

    public function run(Writer $writer) {
        $files = \DataMap\FileMap::F()->get_by_field_name('poster_image');
        if (count($files)) {
            if (!\ImageFly\MediaContextInfo::F()->context_exists('lent_poster')) {
                \ImageFly\MediaContextInfo::register_media_context('lent_poster', 1600, 1600, 100, 100);
            }
            $file = $files[0];
            \ImageFly\ImageFly::F()->process_upload_manual('lent_poster', $writer->result_id, md5('poster'), $file);
            if (\ImageFly\ImageFly::F()->image_exists('lent_poster', $writer->result_id, md5('poster'))) {
                \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO media__lent__mode (id,mode,message,lent_image_name) VALUES(:Pi,'poster','',:Pn) ON DUPLICATE KEY UPDATE lent_image_name=VALUES(lent_image_name);")
                        ->push_params([
                            ":Pi" => $writer->result_id,
                            ":Pn" => md5('poster'),
                        ])->execute_transact();
            }
        }
    }

}
