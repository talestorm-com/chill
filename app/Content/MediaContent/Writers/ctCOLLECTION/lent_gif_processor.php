<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctCOLLECTION;

/**
 * Description of lent_gif_processor
 *
 * @author eve
 */
class lent_gif_processor {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static ();
    }

    public function run(Writer $writer) {
        //если нет картнки но есть гифка - сгенрить картинку из гифки
        if (!\ImageFly\ImageFly::F()->image_exists('lent_poster', $writer->result_id, md5('poster'))) {
            $files = \DataMap\FileMap::F()->get_by_field_name('gif_image');
            if (count($files)) {
                $file = $files[0];
                if (!\ImageFly\MediaContextInfo::F()->context_exists('lent_poster')) {
                    \ImageFly\MediaContextInfo::register_media_context('lent_poster', 1600, 1600, 100, 100);
                }
                $this->run_with_file($file, $writer);
            }
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
                $filex = \DataMap\FakeUploadedFSFile::F($tmp_name);
                $filex->set_content_type('image/jpeg');
                \ImageFly\ImageFly::F()->process_upload_manual('lent_poster', $writer->result_id, md5('poster'), $filex);
                if (\ImageFly\ImageFly::F()->image_exists('lent_poster', $writer->result_id, md5('poster'))) {
                    \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO media__lent__mode (id,mode,message,lent_image_name) VALUES(:Pi,'poster','',:Pn) ON DUPLICATE KEY UPDATE lent_image_name=VALUES(lent_image_name);")
                            ->push_params([
                                ":Pi" => $writer->result_id,
                                ":Pn" => md5('poster'),
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
