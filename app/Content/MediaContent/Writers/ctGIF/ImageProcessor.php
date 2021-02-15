<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctGIF;

/**
 * Description of ImageProcessor
 *
 * @author eve
 */
class ImageProcessor {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(Writer $writer) {
        $map = \DataMap\FileMap::F();
        $file = $map->get_by_field_name("gif_file");
        if (count($file)) {
            $file = $file[0];
            /** @var $file \DataMap\UploadedFile */
            $this->run_with_file($file, $writer);
        }
    }

    protected function run_with_file(\DataMap\UploadedFile $file, Writer $writer) {
        $tmp_name = null;
        try {
            $tmp_name = tempnam(sys_get_temp_dir(), md5(__METHOD__));
            $im = new \Imagick();
            $im->readimage($file->tmp_name);
            $im->coalesceImages();
            foreach ($im as $frame) {
                $frame->setImageBackgroundColor('white');
                $frame->setImageAlphaChannel(\Imagick::ALPHACHANNEL_TRANSPARENT);
                $frame->setImageFormat("jpg");
                $frame->stripImage();
                $frame->writeImage($tmp_name);
                $frame->clear();
                $frame->destroy();
                $image_md = \ImageFly\ImageFly::F()->add_image_from_file($tmp_name, \Content\MediaContent\Readers\ctGIF\MediaContentObject::MEDIA_CONTEXT, $writer->result_id);
                if ($image_md) {
                    \DB\SQLTools\SQLBuilder::F()->push("UPDATE media__content__gif SET default_poster=:P WHERE id=:PP;")
                            ->push_params([
                                ":P" => $image_md,
                                ":PP" => $writer->result_id,
                            ])->execute_transact();
                }
                break;
            }
            $im->clear();
            $im->destroy();
        } catch (\Throwable $e) {
            \Out\Out::F()->add("debug_conversion_error", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'backtrace' => $e->getTraceAsString(),
            ]);
        }
        if ($tmp_name && file_exists($tmp_name) && is_file($tmp_name) && is_writable($tmp_name)) {
            @unlink($tmp_name);
        }
    }

}
