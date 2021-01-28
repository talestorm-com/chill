<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\last_products;

/**
 * @property \DataModel\Product\Model\ProductModel[] $items 
 */
class LastProducts extends \Content\Content implements \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator;

    /** @var LastProducts */
    protected static $instance;

    /** @var  \DataModel\Product\Model\ProductModel[] */
    protected $items;

    protected function __get__items() {
        static::$instance = $this; 
        return $this->items;
    }

    protected function __construct() {
        $this->items = \DataMap\SessionDataMap::F()->get_filtered(static::session_key(), ["NEArray", "DefaultEmptyArray"]);
        if (count($this->items)) {
            $this->items = array_slice($this->items, 0, count($this->items));
        }
    }

    private static function session_key(): string {
        return md5(__METHOD__);
    }

    public static function register(\Content\Product\Product $product) {
        $last_products = \DataMap\SessionDataMap::F()->get_filtered(static::session_key(), ["NEArray", "DefaultEmptyArray"]);
        $key = implode("", ["P", $product->product->id]);
        if (array_key_exists($key, $last_products)) {
            unset($last_products[$key]);
        }
        $last_products[$key] = $product->product;
        if (count($last_products) > 25) {
            $last_products = array_slice($last_products, count($last_products) - 25);
        }
        \DataMap\SessionDataMap::F()->set(static::session_key(), $last_products);
    }

    public static function F(): LastProducts {
        return static::$instance ? static::$instance : new static();
    }

}
