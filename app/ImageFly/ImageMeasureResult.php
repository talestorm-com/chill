<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * Description of ImageMeasureResult
 *
 * @author eve
 * @property string $type
 * @property int    $width
 * @property int    $height
 * @property float  $aspect
 */
class ImageMeasureResult {

    use \common_accessors\TCommonAccess;

    //put your code here
    const IMAGE_TYPE_VIDEO = "video";
    const IMAGE_TYPE_IMAGE = "image";

    /** @var string */
    protected $type;

    /** @var int */
    protected $width;

    /** @var int */
    protected $height;

    /** @return string */
    protected function __get__type() {
        return $this->type;
    }

    /** @return int */
    protected function __get__width() {
        return $this->width;
    }

    /** @return int */
    protected function __get__height() {
        return $this->height;
    }

    /** @return float */
    protected function __get__aspect() {
        return intval($this->width) / max([1, intval($this->height)]);
    }

    protected function __construct(string $type, int $width, int $height) {
        $this->type = static::IMAGE_TYPE_IMAGE === $type || static::IMAGE_TYPE_VIDEO === $type ? $type : \Errors\common_error::R("invalid measurement type");
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * 
     * @param string $type
     * @param int $width
     * @param int $height
     * @return \ImageFly\ImageMeasureResult
     */
    public static function F(string $type, int $width, int $height): ImageMeasureResult {
        return new static($type, $width, $height);
    }

}
