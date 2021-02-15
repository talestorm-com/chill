<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video\Writer;

/**
 * Description of ItemsImageUploader
 *
 * @author eve
 */
class ItemsImageUploader {

    public function run(VideoGroupWriter $w) {
        \ImageFly\MediaContextInfo::register_media_context(\Content\Video\VideoItem::MEDIA_CONTEXT, 2600, 2600, 10, 10);
        $ref = $w->runtime->get_filtered("item_writer_writed", ['ArrayOfNEString', 'NEArray', 'DefaultNull']);
        if ($ref) {
            foreach ($ref as $row) {
                $uid = $row;
                $file = \DataMap\FileMap::F()->get_by_field_name("video_item_image_{$uid}");
                if (count($file)) {
                    try {
                        \ImageFly\ImageFly::F()->process_upload_manual(\Content\Video\VideoItem::MEDIA_CONTEXT, "{$w->result_id}_{$uid}", md5($uid), $file[0]);
                    } catch (\Throwable $e) {
                        $w->append_message(sprintf("%s in %s at %s ", $e->getMessage(), $e->getFile(), $e->getLine()));
                    }
                }
            }
        }
        $this->remove_images($w);
    }

    protected function remove_images(VideoGroupWriter $w) {
        $ref = $w->runtime->get_filtered("item_writer_remove_img", ["ArrayOfNEString", "NEArray", "DefaultNull"]);
        if ($ref) {
            foreach ($ref as $row) {
                \ImageFly\ImageFly::F()->remove_images(\Content\Video\VideoItem::MEDIA_CONTEXT, "{$w->result_id}_{$row}");
            }
        }
    }

    public function __construct() {
        ;
    }

    public static function F(): ItemsImageUploader {
        return new static();
    }

}
