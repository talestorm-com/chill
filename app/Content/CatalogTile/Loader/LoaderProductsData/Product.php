<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile\Loader\LoaderProductsData;

/**
 * @property string $key
 * @property int $id
 * @property string $alias
 * @property string $article
 * @property double $retail
 * @property double $gross
 * @property double $retail_old
 * @property double $gross_old
 * @property double $discount_retail
 * @property double $discount_gross
 * @property string $name
 * @property string $default_image
 * @property ColorCollection $colors
 * @property SizeCollection $sizes
 * @property bool $valid
 */
class Product implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $key;

    /** @var int */
    protected $id;

    /** @var string */
    protected $alias;

    /** @var string */
    protected $article;

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

    /** @var string */
    protected $name;

    /** @var string */
    protected $default_image;

    /** @var ColorCollection */
    protected $colors;

    /** @var SizeCollection */
    protected $sizes;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__key() {
        return $this->key;
    }

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    /** @return string */
    protected function __get__article() {
        return $this->article;
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

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__default_image() {
        return $this->default_image;
    }

    /** @return ColorCollection */
    protected function __get__colors() {
        return $this->colors;
    }

    /** @return SizeCollection */
    protected function __get__sizes() {
        return $this->sizes;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->valid;
    }

    //</editor-fold>

    public function __construct(array $raw_data) {
        $this->import_props($raw_data);        
        $this->colors = ColorCollection::F();
        $this->sizes = SizeCollection::F();
    }

    protected function t_common_import_get_filters() {
        
        return [
            "key" => ["Trim","NEString","DefaultNull"], //string
            "id" => ["IntMore0","DefaultNull"], //int
            "alias" => ["Strip","Trim",'NEString','DefaultNull'], //string
            "article" => ["Strip","Trim",'NEString','DefaultNull'], //string
            "retail" => ["Float","DefaultNull"], //double
            "gross" => ["Float","DefaultNull"], //double
            "retail_old" => ["Float","DefaultNull"], //double
            "gross_old" => ["Float","DefaultNull"], //double
            "discount_retail" => ["Float","DefaultNull"], //double
            "discount_gross" => ["Float","DefaultNull"], //double
            "name" => ["Strip","Trim",'NEString','DefaultNull'], //string
            "default_image" => ["Strip","Trim",'NEString','DefaultNull'], //string            
        ];
    }
    
    /**
     * 
     * @param array $raw
     * @return \Content\CatalogTile\Loader\LoaderProductsData\Product
     */
    public static function F(array $raw):Product{
        return new static($raw);
    }

}
