<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Basket;

/**
 * @property string $hash
 * @property int $product_id
 * @property int $storage_id
 * @property string $color
 * @property int $size
 * @property int $qty
 */
class StorageResultItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props and getters">
    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $hash;

    /** @var int */
    protected $product_id;

    /** @var int */
    protected $storage_id;

    /** @var string */
    protected $color;

    /** @var int */
    protected $size;

    /** @var int */
    protected $qty;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="gettters">
    /** @return string */
    protected function __get__hash() {
        return $this->hash;
    }

    /** @return int */
    protected function __get__product_id() {
        return $this->product_id;
    }

    /** @return int */
    protected function __get__storage_id() {
        return $this->storage_id;
    }

    /** @return string */
    protected function __get__color() {
        return $this->color;
    }

    /** @return int */
    protected function __get__size() {
        return $this->size;
    }

    /** @return int */
    protected function __get__qty() {
        return $this->qty;
    }

    //</editor-fold>
    //</editor-fold>



    public function __construct(array $data) {
        $this->import_props($data);
    }

    protected function t_common_import_get_filters() {
        return [
            'hash' => ["Trim", "NEString", "DefaultNull"],
            'product_id' => ["IntMore0", "DefaultNull"],
            'storage_id' => ["IntMore0", "DefaultNull"],
            'color' => ["Trim", "NEString", "DefaultNull"],
            'size' => ["IntMore0", 'DefaultNull'],
            'qty' => ["IntMore0", "Default0"],
        ];
    }

    /**
     * 
     * @param array $data
     * @return \Basket\StorageResultItem
     */
    public static function F(array $data): StorageResultItem {
        return new static($data);
    }

}
