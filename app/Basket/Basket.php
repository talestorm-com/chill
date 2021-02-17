<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Basket;

/**
 * @property string $version
 * @property bool $empty
 * @property bool $notempty
 * @property string $dependency_value
 */
class Basket implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var BasketItem[] */
    protected $items;

    /** @var BasketItem[] */
    protected $index;

    /** @var string */
    protected $version;

    /** @var string */
    protected $dependency_value;

    /** @var Basket */
    protected static $instance;

    //<editor-fold defaultstate="collapsed" desc="getters">
    protected function __get__version() {
        return $this->version;
    }

    /** @return bool */
    protected function __get__empty() {
        return count($this->items) ? false : true;
    }

    /** @return bool */
    protected function __get__notempty() {
        return count($this->items) ? true : false;
    }

    /** @return string */
    protected function __get__dependency_value() {
        return $this->dependency_value;
    }

    /**
     * 
     * @return \Basket\Basket
     */
    protected function reindex(): Basket {
        $this->index = [];
        foreach ($this->items as $item) {
            $this->index[$item->hash] = $item;
        }
        return $this;
    }

    /**
     * 
     * @return \Basket\Basket
     */
    protected function reindex_if_need(): Basket {
        if (!is_array($this->index)) {
            $this->reindex();
        }
        return $this;
    }

    /**
     * 
     * @param string $hash
     * @param mixed $def
     * @return \Basket\BasketItem
     */
    public function get_item_by_hash(string $hash, $def = null) {
        $this->reindex_if_need();
        return array_key_exists($hash, $this->index) && is_object($this->index[$hash]) && ($this->index[$hash] instanceof BasketItem) ? $this->index[$hash] : $def;
    }

    /**
     * 
     * @param int $product_id
     * @param string $color_id
     * @param \DataModel\Product\Model\ProductSize[] $sizes
     * @param int $qty
     * @param \DataModel\Product\Model\ProductModel $product
     * @return \Basket\Basket
     */
    public function add(int $product_id, string $color_id = null, array $sizes = null, int $qty = 1, \DataModel\Product\Model\ProductModel $product = null): Basket {
        $item = new BasketItem($product_id, $color_id, $sizes, $qty);

        $exists_item = $this->reindex_if_need()->get_item_by_hash($item->hash);
        if ($exists_item) {
            $exists_item->increment($item->qty);
        } else {
            $this->index[$item->hash] = $item;
            $this->items[] = $item;
            $product ? $item->update_product_info($product) : false;
        }
        if ($this->count() === 1) {
            $this->dependency_value = $this->get_dependency()->get_dependency_current_value();
        }
        $this->save();
        return $this;
        /**
         * первый товар - сохраняем dep_value
         * потом меняем его только при ревалидации
         */
    }

    public function remove_item_by_hash(string $hash) {
        $ni = [];
        foreach ($this->items as $item) {
            if ($item->hash !== $hash) {
                $ni[] = $item;
            }
        }
        $this->items = $ni;
        $this->index = null;
        if (!count($this->items)) {
            $this->dependency_value = null;
        }
        $this->save();
        return $this;
    }

    /**
     * 
     * @return \Cache\ICacheDependency
     */
    protected function get_dependency() {
        return \Cache\FileBeaconDependency::F(implode(",", [\DataModel\Product\Model\ProductModel::CACHE_BEAKON_DEP, \CatalogTree\CatalogTree::CACHE_BEAKON_DEPENDENCY]));
    }

    //</editor-fold>

    protected static function get_file_ver() {
        return md5(implode(",", [__FILE__, filemtime(__FILE__)]));
    }

    protected static function session_key() {
        return md5(get_called_class());
    }

    protected function __construct() {
        $this->items = [];
        $this->version = static::get_file_ver();
        static::$instance = $this;
        $this->save();
    }

    /**
     * 
     * @return \Basket\Basket
     */
    public function save(): Basket {
        $this->clearify();
        \DataMap\SessionDataMap::F()->set(static::session_key(), $this);
        return $this;
    }

    protected function clearify() {
        $ni = [];
        foreach ($this->items as $item) {
            if ($item->qty) {
                $ni[] = $item;
            }
        }
        $this->items = $ni;
        $this->index = null;
    }

    /**
     * 
     * @return \Basket\Basket
     */
    public function reset(): Basket {
        $this->items = [];
        return $this->save();
    }

    /**
     * 
     * @return \Basket\Basket
     */
    public static function F(): Basket {
        if (static::$instance) {
            return static::$instance;
        }
        return static::factory();
    }

    protected static function factory() {
        $value = \DataMap\SessionDataMap::F()->get(static::session_key(), null); /* @var $value static */
        $cs = static::class;
        if ($value && is_object($value) && ( $value instanceof $cs) && $value->version === static::get_file_ver()) {
            static::$instance = $value;
            return $value;
        }
        return new static();
    }

    public function __sleep() {
        return ['items', 'version', 'dependency_value'];
    }

    public function __wakeup() {
        $this->index = null;
    }

    protected function get_invalids_count() {
        $c = 0;
        foreach ($this->items as $item) {
            if (!$item->data_valid) {
                $c++;
            }
        }
        return $c;
    }

    public function revalidate_if_need() {
        // ревалидация нужна в случаях:
        //1) есть товары не до конца заполненные
        //2) сломан депенд
        //3) депенда нет вообще
        $rev_need = false;
        if ($this->get_invalids_count()) {
            $rev_need = true;
        }
        if (!$rev_need && !$this->dependency_value) {
            $rev_need = true;
        }
        if (!$rev_need && $this->get_dependency()->get_dependency_current_value() !== $this->dependency_value) {
            $rev_need = true;
        }
        if ($rev_need) {
            return $this->revalidate();
        }
        return null;
    }

    protected function revalidate() {
        $ids = [];
        foreach ($this->items as $item) {
            $ids[] = $item->id;
        }
        $ids = array_unique($ids);
        if (count($ids)) {
            $products = \DataModel\Product\Model\ProductModel::load_array($ids);
            $products_index = [];
            foreach ($products as $product) {
                $key = "P{$product->id}";
                $products_index[$key] = $product;
            }
            foreach ($this->items as $item) {
                $key = "P{$item->id}";
                if (array_key_exists($key, $products_index)) {
                    $item->update_product_info($products_index[$key]);
                } else {
                    $item->update_product_info(null);
                }
            }
            $ni = [];
            foreach ($this->items as $item) {
                if ($item->qty && $item->data_valid) {
                    $ni[] = $item;
                }
            }
            $this->items = $ni;
            $this->index = null;
            $this->dependency_value = $this->get_dependency()->get_dependency_current_value();
            return $products;
        }
        return null;
    }

    protected function t_default_marshaller_export_property_index() {
        return 1;
    }

    public function process_history(array $history) {
        foreach ($history as $history_item) {
            $hic = \Filters\FilterManager::F()->apply_filter_array($history_item, [
                "i" => ["Strip", 'Trim', 'NEString',],
                'a' => ['Strip', 'Trim', 'NEString',],
                'p' => ['IntMore0', 'DefaultNull',]
            ]);
            if (\Filters\FilterManager::F()->is_values_ok($hic)) {
                $this->process_history_action($hic['a'], $hic['i'], $hic['p']);
            }
        }
        $this->save();
        return $this;
    }

    protected function process_history_action(string $action, string $hash, int $param = null) {
        $action_name = "_history_action_{$action}";
        if (method_exists($this, $action_name)) {
            $this->$action_name($hash, $param);
        }
        return $this;
    }

    protected function _history_action_INC(string $hash, int $param = null) {
        $item = $this->get_item_by_hash($hash);
        if ($item) {
            $item->increment();
        }
        return $this;
    }

    protected function _history_action_DEC(string $hash, int $param = null) {
        $item = $this->get_item_by_hash($hash);
        if ($item) {
            $item->decrement();
        }
        return $this;
    }

    protected function _history_action_SET(string $hash, int $param = null) {
        $param = max(intval($param), 1);
        $item = $this->get_item_by_hash($hash);
        if ($item) {
            $item->set_qty($param);
        }
        return $this;
    }

    public function clear() {
        $this->items = [];
        $this->dependency_value = null;
        $this->index = null;
        $this->save();
    }

}
