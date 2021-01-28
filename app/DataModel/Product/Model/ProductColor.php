<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Model;

/**
 * @property string $guid
 * @property string $exchange_uid
 * @property string $name
 * @property int $sort
 * @property bool $image_exists
 * @property bool $valid 
 * @property string $html_color
 */
class ProductColor implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $guid;

    /** @var string */
    protected $exchange_uid;

    /** @var string */
    protected $name;

    /** @var int */
    protected $sort;

    /** @var bool */
    protected $image_exists = false;

    /** @var string */
    protected $html_color;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__guid() {
        return $this->guid;
    }

    /** @return string */
    protected function __get__exchange_uid() {
        return $this->exchange_uid;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return bool */
    protected function __get__image_exists() {
        return $this->image_exists;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->guid && $this->name && $this->html_color;
    }

    protected function __get__html_color() {
        return $this->html_color;
    }

    //</editor-fold>

    public function __construct(array $x) {
        $this->import_props($x);
    }

    protected function t_common_import_get_filters() {
        return [
            'guid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'exchange_uid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'html_color' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'sort' => ['Int', 'Default0'],
        ];
    }

    public static function F(array $x): ProductColor {
        return new static($x);
    }

   
    
    public function check_image_exists(\ImageFly\ImageInfoManager $iim){
        $this->image_exists = $iim->check_color_image_exists($this->guid);
    }

}
