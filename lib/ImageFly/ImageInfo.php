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
 * @property string $image 
 * @property string $name 
 * @property string $title
 * @property int $sort 
 * @property double $start_x 
 * @property double $start_y 
 * @property double $end_x 
 * @property double $end_y
 * @property double $crop_start_x 
 * @property double $crop_start_y 
 * @property double $crop_end_x 
 * @property double $crop_end_y
 * @property bool $can_be_cropped
 * @property bool $outsided true if crop frame larger then image
 * @property bool $valid
 * @property ImagePropertyCollection $properties
 */
class ImageInfo implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $context;

    /** @var string */
    protected $owner_id;

    /** @var string */
    protected $image;

    /** @var string */
    protected $title;

    /** @var integer */
    protected $sort;

    /** @var double */
    protected $crop_start_x;

    /** @var double */
    protected $crop_start_y;

    /** @var double */
    protected $crop_end_x;

    /** @var double */
    protected $crop_end_y;

    /** @var ImagePropertyCollection */
    protected $properties;

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
    protected function __get__image() {
        return $this->image;
    }

    /** @return string */
    protected function __get__name() {
        return $this->image;
    }

    /** @return string */
    protected function __get__title() {
        return $this->title;
    }

    /** @return integer */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return double */
    protected function __get__start_x() {
        return $this->crop_start_x;
    }

    /** @return double */
    protected function __get__start_y() {
        return $this->crop_start_y;
    }

    /** @return double */
    protected function __get__end_x() {
        return $this->crop_end_x;
    }

    /** @return double */
    protected function __get__end_y() {
        return $this->crop_end_y;
    }

    /** @return double */
    protected function __get__crop_start_x() {
        return $this->crop_start_x;
    }

    /** @return double */
    protected function __get__crop_start_y() {
        return $this->crop_start_y;
    }

    /** @return double */
    protected function __get__crop_end_x() {
        return $this->crop_end_x;
    }

    /** @return double */
    protected function __get__crop_end_y() {
        return $this->crop_end_y;
    }

    /** @return bool */
    protected function __get__can_be_cropped() {
        return !(is_null($this->crop_start_x) || is_null($this->crop_start_y) || is_null($this->crop_end_x) || is_null($this->crop_end_y));
    }

    /** @return bool */
    protected function __get__outsided() {
        return $this->__get__can_be_cropped() && (($this->crop_start_x < 0 || $this->crop_start_y < 0 || $this->crop_end_x < 0 || $this->crop_end_y < 0) || ($this->crop_start_x > 100 || $this->crop_start_y > 100 || $this->crop_end_x > 100 || $this->crop_end_y > 100));
    }

    /** @return bool */
    protected function __get__valid() {
        return ($this->context && $this->image && $this->owner_id) ? true : false;
    }

    protected function __get__properties() {
        return $this->properties;
    }

    //</editor-fold>


    public function __construct(array $m = null) {
        if ($m) {
            $this->import_props($m);
        }
    }

    protected function t_common_import_get_filters() {
        return [
            'context' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'owner_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'title' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'sort' => ['AnyInt', 'Default0'],
            'crop_start_x' => ['Float', 'DefaultNull'],
            'crop_start_y' => ['Float', 'DefaultNull'],
            'crop_end_x' => ['Float', 'DefaultNull'],
            'crop_end_y' => ['Float', 'DefaultNull'],
        ];
    }

    protected function t_common_import_after_import() {
        if ($this->__get__valid()) {
            $this->properties = ImagePropertyCollection::F($this->context, $this->owner_id, $this->image);
        }
    }

    /**
     * 
     * @param array $props
     * @return \ImageFly\ImageInfo
     */
    public function set_extended_properties(array $props): ImageInfo {
        $this->properties ? $this->properties->import_array($props, ImagePropertiesFilterDelegate::MODE_READ) : 0;
        return $this;
    }

    /**
     * 
     * @param array $m
     * @return \static
     */
    public static function F(array $m = null): ImageInfo {
        return new static($m);
    }

}
