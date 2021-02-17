<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Ribbon\Writer;

/**
 * Description of ItemsImageUploader
 *
 * @author eve
 */
class ImageUploader {

    public function run(RibbonItemWriter $w) {
        \ImageFly\MediaContextInfo::register_media_context(\Content\Ribbon\RibbonItem::MEDIA_CONTEXT, 2600, 2600, 10, 10);
        $file = \DataMap\FileMap::F()->get_by_field_name("image_field");
        if (count($file)) {
            try {
                $image_name = md5("image");
                \ImageFly\ImageFly::F()->process_upload_manual(\Content\Ribbon\RibbonItem::MEDIA_CONTEXT, "{$w->result_id}", $image_name, $file[0]);
                \DB\SQLTools\SQLBuilder::F()->push("UPDATE ribbon SET image=:P WHERE id=:PP")->push_params([":P" => $image_name, ":PP" => $w->result_id])->execute();
            } catch (\Throwable $e) {
                \DB\SQLTools\SQLBuilder::F()->push("UPDATE ribbon SET image=:P WHERE id=:PP")->push_params([":P" => null, ":PP" => $w->result_id])->execute();
                $w->append_message(sprintf("%s in %s at %s ", $e->getMessage(), $e->getFile(), $e->getLine()));
            }
        }
        $this->remove_images($w);
    }

    protected function remove_images(RibbonItemWriter $w) {
        $need_remove = $w->common_input->get_filtered("image_removed", ["Boolean", "DefaultFalse"]) ||
                $w->data_input->get_filtered("image_removed", ["Boolean", "DefaultFalse"]);
        if ($need_remove) {
            \ImageFly\ImageFly::F()->remove_images(\Content\Ribbon\RibbonItem::MEDIA_CONTEXT, "{$w->result_id}");
            \DB\SQLTools\SQLBuilder::F()->push("UPDATE ribbon SET image=:P WHERE id=:PP")->push_params([":P" => null, ":PP" => $w->result_id])->execute();
        }
    }

    public function __construct() {
        ;
    }

    public static function F(): ImageUploader {
        return new static();
    }

}
