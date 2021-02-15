<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Model;

/**
 * @property int $id
 * @property string $guid
 * @property string $article
 * @property string $alias
 * @property string $name
 * @property string $default_image
 * @property double $retail
 * @property double $gross
 * @property double $retail_old
 * @property double $gross_old
 * @property double $discount_retail
 * @property double $discount_gross 
 * @property bool $enabled
 * @property int $sort
 * @property bool $valid
 * @property ProductColorCollection $colors
 * @property ProductSizeCollection $sizes
 */
class ProductCross implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $id;

    /** @var string */
    protected $guid;

    /** @var string */
    protected $article;

    /** @var string */
    protected $alias;

    /** @var string */
    protected $name;

    /** @var string */
    protected $default_image;

    /** @var double */
    protected $retail;

    /** @var double */
    protected $gross;

    /** @var double */
    protected $retail_old;

    /** @var double */
    protected $gross_old;

    /** @var double */
    protected $discount_retail;

    /** @var double */
    protected $discount_gross;

    /** @var int */
    protected $sort;

    /** @var bool */
    protected $enabled;

    /** @var ProductColorCollection */
    protected $colors;

    /** @var ProductSizeCollection */
    protected $sizes;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__guid() {
        return $this->guid;
    }

    /** @return string */
    protected function __get__article() {
        return $this->article;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__default_image() {
        return $this->default_image;
    }

    /** @return double */
    protected function __get__retail() {
        return $this->retail;
    }

    /** @return double */
    protected function __get__gross() {
        return $this->gross;
    }

    /** @return double */
    protected function __get__retail_old() {
        return $this->retail_old;
    }

    /** @return double */
    protected function __get__gross_old() {
        return $this->gross_old;
    }

    /** @return double */
    protected function __get__discount_retail() {
        return $this->discount_retail;
    }

    /** @return double */
    protected function __get__discount_gross() {
        return $this->discount_gross;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    protected function __get__valid() {
        return ($this->id && $this->article && $this->name) ? true : false;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return ProductColorCollection */
    protected function __get__colors() {
        return $this->colors;
    }

    /** @return ProductSizeCollection */
    protected function __get__sizes() {
        return $this->sizes;
    }

    //</editor-fold>


    protected function t_common_import_get_filters() {
        return [
            "id" => ['IntMore0', 'DefaultNull'], //int
            "guid" => ['Trim', 'NEString', 'DefaultNull'], //string
            "article" => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            "alias" => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            "name" => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            "default_image" => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'retail' => ['Float', 'DefaultNull'],
            'gross' => ['Float', 'DefaultNull'],
            'retail_old' => ['Float', 'DefaultNull'],
            'gross_old' => ['Float', 'DefaultNull'],
            'discount_retail' => ['Float', 'DefaultNull'],
            'discount_gross' => ['Float', 'DefaultNull'],
            "sort" => ["Int", 'Default0'], //int
            "enabled" => ["Boolean", "DefaultTrue"],
        ];
    }

    public function __construct(array $s) {
        $this->import_props($s);
        $this->colors = ProductColorCollection::F(0, true);
        $this->sizes = ProductSizeCollection::F(0, true);
    }

    /**
     * 
     * @param array $x
     * @return \DataModel\Product\Model\ProductCross
     */
    public static function F(array $x): ProductCross {
        return new static($x);
    }

}
