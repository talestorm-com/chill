<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Gallery;

/**
 * Description of ProtectedImageUploader
 * Загрузка картинок!! видео грузится отдельным классом
 * @author eve
 * 
 * /base/owner_id/collection_uid/image_uid|cover.jpg/webm/mov
 */
class Uploader {

    /** @var string */
    private $base_path;

    const MAX_DIM = 2500;
    const MAX_DIM_COVER = 500;
    const MAX_DIM_PREVIEW = 500;

    /** @var Uploader */
    private static $instance;

    private function __construct() {
        static::$instance = $this;
        $this->base_path = rtrim(\Config\Config::F()->PUBLIC_STORAGE_BASE, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public static function F(): Uploader {
        return static::$instance ? static::$instance : new static();
    }

    public function upload_gallery_preview(\DataMap\UploadedFile $file, \PublicMedia\PublicMediaGallery $gallery) {
        if ($file->valid) {
            $this->set_gallery_preview_by_path($file->tmp_name, $gallery);
        }
        return $this;
    }
    
    

    public function set_gallery_preview_by_path(string $path, \PublicMedia\PublicMediaGallery $gallery) {
        $dir = $gallery->get_files_path();
        if (!(file_exists($dir) && is_dir($dir) && is_readable($dir))) {
            @mkdir($dir, 0777, true);
        }
        if (!(file_exists($dir) && is_dir($dir) && is_readable($dir))) {
            \Errors\common_error::RF("can`t create path `%s` in `%s`", $path, __METHOD__);
        }
        $filename = $dir . "cover.jpg";
        $image = $this->prepare_image_for_gallery_preview($path);
        $image_ms = \ImageFly\ImageInfoDetector::F()->measure_image_imagic($image);
        $aspect = $image_ms->aspect;
        if (file_exists($filename) && is_file($filename) && is_writable($filename)) {
            @unlink($filename);
        }
        $image->writeImage($filename);
        \DB\SQLTools\SQLBuilder::F()->push("UPDATE public__gallery SET cover_aspect=:P WHERE id=:PP;")
                ->push_params([":P" => $aspect, ":PP" => $gallery->id])->execute();
    }

    /**
     * 
     * @param string $path
     * @return  \Imagick
     */
    public function prepare_image_for_gallery_preview(string $path) {
        return $this->resize_image_max($path, static::MAX_DIM_COVER);
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

    public function upload_protected_gallery_cover(string $gallery_uid, int $owner_id) {
        $path = $this->base_path . DIRECTORY_SEPARATOR . $owner_id . DIRECTORY_SEPARATOR . $gallery_uid . DIRECTORY_SEPARATOR;
        if (!(file_exists($path) && is_dir($path) && is_readable($path))) {
            @mkdir($path, 0777, true);
        }
        if (!(file_exists($path) && is_dir($path) && is_readable($path))) {
            ImageFlyError::RF("can`t create path `%s` in `%s`", $path, __METHOD__);
        }
        $file = \DataMap\FileMap::F()->get_by_index();
        if ($file && $file->valid) {
            $image = new \Imagick();
            $image->readImage($file->tmp_name);
            if (max([$image->getImageWidth(), $image->getImageHeight()]) > static::MAX_DIM_COVER) {
                $kx = static::MAX_DIM_COVER / max([$image->getImageWidth(), $image->getImageHeight()]);
                $target_width = (int) round($image->getImageWidth() * $kx, 0);
                $target_height = (int) round($image->getImageHeight() * $kx, 0);
                $newimage = new \Imagick();
                $newimage->newimage($target_width, $target_height, new \ImagickPixel('rgba(0,0,0,0)'));
                $image->resizeimage($target_width, $target_height, \Imagick::FILTER_POINT, .99);
                $newimage->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, 0, 0);
                $image->clear();
                $image = $newimage;
            }
        }
        $image->flattenImages();
        $image->setFormat("jpg");
        $image->setImageFormat("jpg");
        $image->setCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $filename = $path . "cover.jpg";
        if (file_exists($filename)) {
            @unlink($filename);
        }
        $image->writeImage($filename);
    }

    public function upload_protected_gallery_image(string $gallery_uid, string $uid, int $owner_id, \DataMap\UploadedFile $file) {
        $path = $this->base_path . DIRECTORY_SEPARATOR . $owner_id . DIRECTORY_SEPARATOR . $gallery_uid . DIRECTORY_SEPARATOR;
        if (!(file_exists($path) && is_dir($path) && is_readable($path))) {
            @mkdir($path, 0777, true);
        }
        if (!(file_exists($path) && is_dir($path) && is_readable($path))) {
            ImageFlyError::RF("can`t create path `%s` in `%s`", $path, __METHOD__);
        }
        if ($file && $file->valid) {
            $image = new \Imagick();
            $image->readImage($file->tmp_name);
            if (max([$image->getImageWidth(), $image->getImageHeight()]) > static::MAX_DIM) {
                $kx = static::MAX_DIM / max([$image->getImageWidth(), $image->getImageHeight()]);
                $target_width = (int) round($image->getImageWidth() * $kx, 0);
                $target_height = (int) round($image->getImageHeight() * $kx, 0);
                $newimage = new \Imagick();
                $newimage->newimage($target_width, $target_height, new \ImagickPixel('rgba(0,0,0,0)'));
                $image->resizeimage($target_width, $target_height, \Imagick::FILTER_POINT, .99);
                $newimage->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, 0, 0);
                $image->clear();
                $image = $newimage;
            }
            $image->flattenImages();
            $image->setFormat("jpg");
            $image->setImageFormat("jpg");
            $image->setCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $image->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $filename = $path . "{$uid}.jpg";
            if (file_exists($filename)) {
                @unlink($filename);
            }
            $image->writeImage($filename);
        }
    }

    public function upload_protected_gallery_video(string $gallery_uid, string $uid, int $owner_id, \DataMap\UploadedFile $file, string $extension) {
        $path = $this->base_path . DIRECTORY_SEPARATOR . $owner_id . DIRECTORY_SEPARATOR . $gallery_uid . DIRECTORY_SEPARATOR;
        if (!(file_exists($path) && is_dir($path) && is_readable($path))) {
            @mkdir($path, 0777, true);
        }
        if (!(file_exists($path) && is_dir($path) && is_readable($path))) {
            ImageFlyError::RF("can`t create path `%s` in `%s`", $path, __METHOD__);
        }
        if ($file && $file->valid) {
            $extension = \Helpers\Helpers::NEString($extension, null);
            $filename = null;
            if ($extension) {
                $filename = $path . "{$uid}.{$extension}";
            } else {
                $filename = $path . "{$uid}";
            }
            $file->move($filename);
        }
    }

    public function upload_preview(string $gallery_uid, string $item_uid, int $user_id, \DataMap\UploadedFile $file) {
        $path = $this->base_path . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR . $gallery_uid . DIRECTORY_SEPARATOR;
        if (!(file_exists($path) && is_dir($path) && is_readable($path))) {
            @mkdir($path, 0777, true);
        }
        if (!(file_exists($path) && is_dir($path) && is_readable($path))) {
            ImageFlyError::RF("can`t access path `%s` in `%s`", $path, __METHOD__);
        }
        if ($file && $file->valid) {
            $image = new \Imagick();
            $image->readImage($file->tmp_name);
            if (max([$image->getImageWidth(), $image->getImageHeight()]) > static::MAX_DIM_PREVIEW) {
                $kx = static::MAX_DIM_PREVIEW / max([$image->getImageWidth(), $image->getImageHeight()]);
                $target_width = (int) round($image->getImageWidth() * $kx, 0);
                $target_height = (int) round($image->getImageHeight() * $kx, 0);
                $newimage = new \Imagick();
                $newimage->newimage($target_width, $target_height, new \ImagickPixel('rgba(0,0,0,0)'));
                $image->resizeimage($target_width, $target_height, \Imagick::FILTER_POINT, .99);
                $newimage->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, 0, 0);
                $image->clear();
                $image = $newimage;
            }
            $image->flattenImages();
            $image->setFormat("jpg");
            $image->setImageFormat("jpg");
            $image->setCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $image->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $filename = $path . "preview.{$item_uid}.jpg";
            if (file_exists($filename)) {
                @unlink($filename);
            }
            $image->writeImage($filename);
        }
    }

    public function create_preview_for_image(string $target_path, string $source_path) {
        if (file_exists($source_path)) {
            $image = new \Imagick($source_path);
            if (max([$image->getImageWidth(), $image->getImageHeight()]) > static::MAX_DIM_PREVIEW) {
                $kx = static::MAX_DIM_PREVIEW / max([$image->getImageWidth(), $image->getImageHeight()]);
                $target_width = (int) round($image->getImageWidth() * $kx, 0);
                $target_height = (int) round($image->getImageHeight() * $kx, 0);
                $newimage = new \Imagick();
                $newimage->newimage($target_width, $target_height, new \ImagickPixel('rgba(0,0,0,0)'));
                $image->resizeimage($target_width, $target_height, \Imagick::FILTER_POINT, .99);
                $newimage->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, 0, 0);
                $image->clear();
                $image = $newimage;
            }
            $image->flattenImages();
            $image->setFormat("jpg");
            $image->setImageFormat("jpg");
            $image->setCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $image->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            if (file_exists($target_path) && is_file($target_path) && is_writable($target_path)) {
                @unlink($target_path);
            }
            $image->writeImage($target_path);
        }
    }

    public function create_preview_for_video(string $target_path, string $source_path) {
        if (file_exists($source_path)) {
            try {
                $tmp_path = tempnam(sys_get_temp_dir(), "prev_take");
                exec("ffmpeg -ss 00:00:01 -i {$source_path} -vframes 1 -q:v 2 {$tmp_path}");
                if (file_exists($tmp_path)) {
                    try {
                        $this->create_preview_for_image($target_path, $tmp_path);
                        @unlink($tmp_path);
                    } catch (\Throwable $x) {
                        @unlink($tmp_path);
                        throw $x;
                    }
                }
            } catch (\Throwable $e) {
                
            }
        }
    }

}
