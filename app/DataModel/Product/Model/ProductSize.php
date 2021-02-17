<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Model;

/**
 * @property int $product_id
 * @property int $id
 * @property string $guid
 * @property string $value 
 * @property bool $enabled
 * @property bool $valid 
 */
class ProductSize implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $product_id;

    /** @var int */
    protected $id;

    /** @var string */
    protected $guid;

    /** @var string */
    protected $value;

    /** @var bool */
    protected $enabled;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__product_id() {
        return $this->product_id;
    }

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__guid() {
        return $this->guid;
    }

    /** @return string */
    protected function __get__value() {
        return $this->value;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->product_id && $this->id && $this->value;
    }

    //</editor-fold>

    public function __construct(array $x) {
        $this->import_props($x);
    }

    protected function t_common_import_get_filters() {
        return [
            'product_id' => ['IntMore0', 'DefaultNull'],
            'id' => ['IntMore0', 'DefaultNull'],
            'guid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'enabled' => ['Boolean', 'DefaultTrue'],
            'value' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    public static function F(array $x): ProductSize {
        return new static($x);
    }

}
