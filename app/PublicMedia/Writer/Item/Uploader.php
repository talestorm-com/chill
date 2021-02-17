<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Item;

/**
 * Description of Uploader
 *
 * @author eve
 */
class Uploader {

    const MAX_DIM = 2500;
    const MAX_DIM_COVER = 500;
    const MAX_DIM_PREVIEW = 500;

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \PublicMedia\Writer\Item\Uploader
     */
    public static function F(): Uploader {
        return new static();
    }

    public function run(Writer $w) {

        if ($w->upload_info->has_file) {
            if ($w->upload_info->is_video) {
                $this->upload_video($w);
            } else {
                $this->upload_image($w);
            }
            if (!$w->upload_info->has_preview) {
                $this->create_item_preview($w);
            }
        }
        if ($w->upload_info->has_preview) {
            $this->set_item_preview($w);
        }
    }

    protected function create_item_preview(Writer $w) {
        if ($w->upload_info->is_video) {
            $this->create_video_preview($w);
        } else {
            $this->create_image_preview($w);
        }
    }

    protected function create_preview_for_image($source_image, $target_path) {
        $image = $this->resize_image_max($source_image, static::MAX_DIM_PREVIEW);
        if (file_exists($target_path) && is_file($target_path)) {
            @unlink($target_path);
        }
        $image->writeImage($target_path);
    }

    protected function create_image_preview(Writer $w) {
        $preview_file_name = $w->medial_object->get_files_path() . "preview.{$w->writed_item->id}.jpg";
        $source_path = $w->medial_object->get_files_path() . "{$w->writed_item->id}.jpg";
        try {
            $this->create_preview_for_image($source_path, $preview_file_name);
        } catch (\Throwable $e) {
            
        }
    }

    protected function create_video_preview(Writer $w) {
        $preview_file_name = $w->medial_object->get_files_path() . "preview.{$w->writed_item->id}.jpg";
        $source_path = $w->medial_object->get_files_path() . "{$w->writed_item->id}{$w->upload_info->dotted_file_extension}";
        //\Out\Out::F()->add("vpdebug", [
        //    $source_path, $preview_file_name
        //]);
        try {
            $tmp_path = tempnam(sys_get_temp_dir(), "prev_take");
            if (file_exists($tmp_path) && is_file($tmp_path)) {
                @unlink($tmp_path);
            }
            $tmp_path .= ".jpg";
            exec("ffmpeg -ss 00:00:01 -i {$source_path} -vframes 1 -q:v 2 {$tmp_path}");
            if (file_exists($tmp_path)) {
                try {
                    $this->create_preview_for_image($tmp_path, $preview_file_name);
                    @unlink($tmp_path);
                } catch (\Throwable $x) {
                    // @unlink($tmp_path);
                    throw $x;
                }
            }
        } catch (\Throwable $e) {
            \Out\Out::F()->add("vpdebugx", [
                $e->getMessage(), $tmp_path,
            ]);
        }
    }

    protected function upload_video(Writer $w) {
        $dir = $w->medial_object->get_files_path();
        if (!(file_exists($dir) && is_dir($dir) && is_readable($dir))) {
            @mkdir($dir, 0777, true);
        }
        if (!(file_exists($dir) && is_dir($dir) && is_readable($dir))) {
            ImageFlyError::RF("can`t access path `%s` in `%s`", $dir, __METHOD__);
        }
        if ($w->upload_info->has_file) {
            $filename = $dir . "{$w->writed_item->id}{$w->upload_info->dotted_file_extension}";
            if (file_exists($filename)) {
                @unlink($filename);
            }
            $w->upload_info->file->move($filename);
        }
    }

    protected function upload_image(Writer $w) {
        $dir = $w->medial_object->get_files_path();
        if (!(file_exists($dir) && is_dir($dir) && is_readable($dir))) {
            @mkdir($dir, 0777, true);
        }
        if (!(file_exists($dir) && is_dir($dir) && is_readable($dir))) {
            ImageFlyError::RF("can`t access path `%s` in `%s`", $dir, __METHOD__);
        }
        if ($w->upload_info->has_file) {
            $image = $this->resize_image_max($w->upload_info->file->tmp_name, static::MAX_DIM);
            $filename = $dir . "{$w->writed_item->id}.jpg";
            if (file_exists($filename)) {
                @unlink($filename);
            }
            $image->writeImage($filename);
        }
    }

    public function set_item_preview(Writer $w) {
        $dir = $w->medial_object->get_files_path();
        if (!(file_exists($dir) && is_dir($dir) && is_readable($dir))) {
            @mkdir($dir, 0777, true);
        }
        if (!(file_exists($dir) && is_dir($dir) && is_readable($dir))) {
            ImageFlyError::RF("can`t access path `%s` in `%s`", $dir, __METHOD__);
        }
        if ($w->upload_info->has_preview) {
            $image = $this->resize_image_max($w->upload_info->preview->tmp_name, static::MAX_DIM_PREVIEW);
            $filename = $dir . "preview.{$w->writed_item->id}.jpg";
            if (file_exists($filename)) {
                @unlink($filename);
            }
            $image->writeImage($filename);
        }
    }

    /**
     * 
     * @param string $path
     * @param int $max_dim
     * @return \Imagick
     */
    public function resize_image_max(string $path, int $max_dim) {
        $image = new \Imagick();
        $image->readImage($path);
        if (max([$image->getImageWidth(), $image->getImageHeight()]) > $max_dim) {
            $kx = $max_dim / max([$image->getImageWidth(), $image->getImageHeight()]);
            $target_width = (int) round($image->getImageWidth() * $kx, 0);
            $target_height = (int) round($image->getImageHeight() * $kx, 0);
            $newimage = new \Imagick();
            $newimage->newimage($target_width, $target_height, new \ImagickPixel('rgba(0,0,0,0)'));
            $image->resizeimage($target_width, $target_height, \Imagick::FILTER_POINT, .99);
            $newimage->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, 0, 0);
            $image->clear();
            $image = $newimage;
        }
        //$image->flattenImages();
        $image->setImageBackgroundColor('white');
        $image->setImageAlphaChannel(11); // Imagick::ALPHACHANNEL_REMOVE
        $image->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
        $image->setFormat("jpg");
        $image->setImageFormat("jpg");
        $image->setCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        return $image;
    }

}
