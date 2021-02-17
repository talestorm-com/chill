<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly\presets;

/**
 * @property string $name
 * @property double $width
 * @property double $height
 * @property string $display
 * @property bool $valid
 */
class ImageFlyAspectPreset implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    private static $class_version = null;

    public static function get_class_version() {
        if (!static::$class_version) {
            static::$class_version = md5(implode("*", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$class_version;
    }

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $name;

    /** @var string */
    protected $display;

    /** @var double */
    protected $width;

    /** @var double */
    protected $height;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__display() {
        return $this->display;
    }

    /** @return double */
    protected function __get__width() {
        return $this->width;
    }

    /** @return double */
    protected function __get__height() {
        return $this->height;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->name && $this->width && $this->height && $this->width > 0 && $this->height > 0 && $this->display;
    }

    //</editor-fold>


    public function __construct(array $data) {
        try {
            $this->import_props($data);
        } catch (\Throwable $e) {
            $this->name = null;
            $this->width = null;
            $this->height = null;
        }
    }

    protected function t_common_import_get_filters(): array {
        return [
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'display' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'width' => ["Float", "DefaultNull"],
            'height' => ["Float", "DefaultNull"]
        ];
    }

    protected function t_common_import_after_import() {
        $this->display = $this->display ? $this->display : $this->name;
        $this->name = $this->name ? $this->name : $this->display;
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data) {
        return new static($data);
    }

}
