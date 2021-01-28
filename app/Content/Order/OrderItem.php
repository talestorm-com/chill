<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Order;

/**
 * @property int $order_id
 * @property string $guid
 * @property int $product_id
 * @property string $name
 * @property string $color_name
 * @property string $article
 * @property string $size
 * @property int $qty
 * @property double $price
 * @property double $amount
 * @property bool $valid
 */
class OrderItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $order_id;

    /** @var string */
    protected $guid;

    /** @var int */
    protected $product_id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $color_name;

    /** @var string */
    protected $article;

    /** @var string */
    protected $size;

    /** @var int */
    protected $qty;

    /** @var double */
    protected $price;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__order_id() {
        return $this->order_id;
    }

    /** @return string */
    protected function __get__guid() {
        return $this->guid;
    }

    /** @return int */
    protected function __get__product_id() {
        return $this->product_id;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__color_name() {
        return $this->color_name;
    }

    /** @return string */
    protected function __get__article() {
        return $this->article;
    }

    /** @return string */
    protected function __get__size() {
        return $this->size;
    }

    /** @return int */
    protected function __get__qty() {
        return $this->qty;
    }

    /** @return double */
    protected function __get__price() {
        return $this->price;
    }

    /** @return double */
    protected function __get__amount() {
        return floatval($this->price * $this->qty);
    }

    protected function __get__valid() {
        return ($this->order_id && $this->guid && $this->name && $this->article) ? true : false;
    }

    //</editor-fold>


    public function __construct(array $data) {
        $this->import_props($data);
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data) {
        return new static($data);
    }

    protected function t_common_import_get_filters() {
        return [
            'order_id' => ["IntMore0", "DefaultNull"], //int
            'guid' => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            'product_id' => ["IntMore0", "DefaultNull"], //int
            'name' => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            'color_name' => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            'article' => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            'size' => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            'qty' => ["IntMore0", "Default0"], //int
            'price' => ["Float", "Default0"], //double
        ];
    }

}
