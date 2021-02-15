<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * Description of ImageInfoDetector
 *
 * @author eve
 */
class ImageInfoDetector {

    private static $instance;

    protected function __construct() {
        static::$instance = $this;
    }

    public static function F(): ImageInfoDetector {
        return static::$instance ? static::$instance : new static();
    }

    public function measure_image_imagic(\Imagick $image): ImageMeasureResult {
        return ImageMeasureResult::F(ImageMeasureResult::IMAGE_TYPE_IMAGE, $image->getImageWidth(), $image->getImageHeight());
    }

    /**
     * 
     * @param \DataMap\UploadedFile $file
     * @return ImageMeasureResult
     */
    public function detect_file(\DataMap\UploadedFile $file) {
        return $this->detect_path($file->tmp_name);
    }

    /**
     * 
     * @param string $path
     * @return ImageMeasureResult
     */
    public function detect_path(string $path) {
        $result = null;
        try {
            $result = $this->check_measure_video($path);
        } catch (\Throwable $e) {
            $result = null;
        }
        if (!$result) {
            try {
                $result = $this->check_measure_image($path);
            } catch (\Throwable $e) {
                $result = null;
            }
        }
        return $result;
    }

    /**
     * 
     * @param string $path
     * @return ImageMeasureResult|null
     */
    protected function check_measure_video(string $path) {
        try {
            $probe = new \ImageFly\FFProbe($path);
            $streams = array_key_exists("streams", $probe->metadata) ? $probe->metadata["streams"] : [];
            foreach ($streams as $stream) {
                $is_video = false;
                $width = 0;
                $height = 0;
                if (is_array($stream)) {
                    if (array_key_exists("codec_tag", $stream)) {
                        if (\Helpers\Helpers::NEString($stream["codec_tag"], null) && $stream["codec_tag"] !== "0x00000000" && $stream["codec_tag"] !== "0x0000") {
                            $is_video = true;
                            if (array_key_exists("width", $stream) && floatval($stream["width"]) > 0) {
                                $width = floatval($stream["width"]);
                            }
                            if (array_key_exists("height", $stream) && floatval($stream["height"]) > 0) {
                                $height = floatval($stream["height"]);
                            }
                            if ($is_video && $height > 0 && $width > 0) {
                                return ImageMeasureResult::F(ImageMeasureResult::IMAGE_TYPE_VIDEO, $width, $height);
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            
        }
        return null;
    }

    /**
     * 
     * @param string $path
     * @return ImageMeasureResult|null
     */
    protected function check_measure_image(string $path) {
        $result = null;
        try {
            $image = new \Imagick();
            $image->readImage($path);
            $result = $this->measure_image_imagic($image);
            $image->destroy();
        } catch (\Throwable $e) {
            $result = null;
        }
        return $result;
    }

}
