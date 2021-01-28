<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * @property string $context
 * @property string $owner_id
 * @property string $id
 * @property string $image
 * @property string $image_name
 * @property string $image_ext
 * @property string $image_extension
 * @property string $image_spec
 * @property string $background
 * @property int $width
 * @property int $height
 * @property bool $use_crop
 * @property bool $crop_fill
 * @property string $preset
 * @property bool $valid
 * @property string $mime
 */
class ImageFlyRequest {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $context;

    /** @var string */
    protected $owner_id;

    /** @var string */
    protected $image_name;

    /** @var string */
    protected $image_ext;

    /** @var string */
    protected $image_spec;

    /** @var string */
    protected $background;

    /** @var int */
    protected $width;

    /** @var int */
    protected $height;

    /** @var bool */
    protected $use_crop;

    /** @var bool */
    protected $crop_fill;

    /** @var string */
    protected $mime;

    /** @var string */
    protected $preset;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__context() {
        return $this->context;
    }

    /** @return string */
    protected function __get__owner_id() {
        return $this->owner_id;
    }

    /** @return string */
    protected function __get__id() {
        return $this->owner_id;
    }

    /** @return string */
    protected function __get__image() {
        return $this->image_name;
    }

    /** @return string */
    protected function __get__image_name() {
        return $this->image_name;
    }

    /** @return string */
    protected function __get__image_ext() {
        return $this->image_ext;
    }

    /** @return string */
    protected function __get__image_extension() {
        return $this->image_ext;
    }

    /** @return string */
    protected function __get__image_spec() {
        return $this->image_spec;
    }

    /** @return string */
    protected function __get__background() {
        return $this->background;
    }

    /** @return int */
    protected function __get__width() {
        return $this->width;
    }

    /** @return int */
    protected function __get__height() {
        return $this->height;
    }

    /** @return bool */
    protected function __get__use_crop() {
        return $this->use_crop;
    }

    /** @return bool */
    protected function __get__crop_fill() {
        return $this->crop_fill;
    }

    /** @return bool */
    protected function __get__valid() {
        return ($this->image_name && $this->context && $this->owner_id) ? true : false;
    }

    protected function __get__mime() {
        return $this->mime;
    }

    protected function __get__preset() {
        return $this->preset;
    }

    //</editor-fold>

    public function __construct(array $a = null) {
        if ($a && is_array($a)) {
            $this->import_props($a);
        }
    }

    /**
     * 
     * @param array $data
     * @return \ImageFly\ImageFlyRequest
     */
    public static function F(array $data = null): ImageFlyRequest {
        return new static($data);
    }

    protected function t_common_import_get_filters() {
        return [
            'context' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'owner_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'image_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'image_spec' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'width' => ['IntMore0', 'Default0'],
            'height' => ['IntMore0', 'Default0'],
            'use_crop' => ['Boolean', 'DefaultTrue'],
            'crop_fill' => ['Boolean', 'DefaultFalse'],
            'background' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            "mime" => ["Strip", "Trim", "NEString", "Lowercase", "DefaultNull"],
            "preset" => ['Strip', "Trim", "NEString", "DefaultNull"],
        ];
    }

    protected function t_common_import_after_import() {
        if (!$this->mime) {
            $this->mime = "jpg";
        }
        if (!$this->preset) {
            $this->preset = "none";
        }
    }

}
