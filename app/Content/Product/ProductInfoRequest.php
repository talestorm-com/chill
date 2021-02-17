<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Product;

/**
 * @property string $version
 * @property string $cache_key
 * @property string $request_alias
 * @property int $request_id
 * @property \DataModel\Product\Model\ProductModel $product
 * @property bool $dealer
 * @property SizeCollection $parsed_sized
 * @property bool $valid
 */
class ProductInfoRequest implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,  
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="fields">
    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $version;

    /** @var string */
    protected $cache_key;

    /** @var string */
    protected $request_alias;

    /** @var int */
    protected $request_id;

    /** @var \DataModel\Product\Model\ProductModel */
    protected $product;

    /** @var bool */
    protected $dealer;

    /** @var SizeCollection */
    protected $parsed_sized;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    /** @return string */
    protected function __get__cache_key() {
        return $this->cache_key;
    }

    /** @return string */
    protected function __get__request_alias() {
        return $this->request_alias;
    }

    /** @return int */
    protected function __get__request_id() {
        return $this->request_id;
    }

    /** @return \DataModel\Product\Model\ProductModel */
    protected function __get__product() {
        return $this->product;
    }

    /** @return bool */
    protected function __get__dealer() {
        return $this->dealer;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->product ? true : false;
    }

    /** @return SizeCollection */
    protected function __get__parsed_sized() {
        return $this->parsed_sized;
    }

    //</editor-fold>
    //</editor-fold>

    protected function __construct(int $id = null, string $alias = null) {
        $this->version = static::get_file_version();
        $this->request_alias = $alias;
        $this->request_id = $id;
        $this->dealer = static::is_dealer();
        if ($this->request_alias) {
            $this->load_by_alias();
        } else {
            $this->load_by_id();
        }
        if ($this->product) {
            $this->parsed_sized = SizeCollection::F($this->product->sizes);
            $this->set_cache();
        }
    }

    protected function set_cache() {
        $this->cache_key = static::get_cache_key($this->request_id, $this->request_alias);
        \Cache\FileCache::F()->put($this->cache_key, $this, 0, \Cache\FileBeaconDependency::F(implode(",", [
                    \DataModel\Product\Model\ProductModel::CACHE_BEAKON_DEP,
                    \CatalogTree\CatalogTree::CACHE_BEAKON_DEPENDENCY,
                    \DataModel\CatalogSizeDef\CatalogSizeDefVoc::CACHE_DEPENDENCY,
        ])));
    }

    protected static function get_file_version() {
        return md5(implode("_", [__FILE__, filemtime(__FILE__)]));
    }

    protected static function get_cache_key(int $id = null, string $alias = null): string {
        return sprintf("C%sI%sA%sD%s", __CLASS__, (string) $id, (string) $alias, (string) static::is_dealer());
    }

    protected static function is_dealer(): bool {
        if (\Auth\Auth::F()->is_authentificated()) {
            if (\Auth\Auth::F()->get_user_info()->is_dealer) {
                if (\Auth\Auth::F()->is(\Auth\Roles\RoleDealer::class)) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function load_by_alias() {
        try {
            $this->product = \DataModel\Product\Model\ProductModel::FA($this->request_alias);
        } catch (\Throwable $e) {
            $this->product = null;
        }
    }

    protected function load_by_id() {
        try {
            $this->product = \DataModel\Product\Model\ProductModel::F($this->request_id);
        } catch (\Throwable $e) {
            $this->product = null;
        }
    }

    /**
     * 
     * @param int $id
     * @param string $alias
     */
    public static function F(int $id = null, string $alias = null): ProductInfoRequest {
        ($id || $alias) ? 0 : \Errors\common_error::R("ProductInfoRequest requires id or alias");
        $cache_key = static::get_cache_key($id, $alias);
        $cached = \Cache\FileCache::F()->get($cache_key); /* @var $cached static */
        $cs = static::class;
        if ($cached && is_object($cached) && ($cached instanceof $cs) && $cached->version === static::get_file_version()) {
            return $cached;
        }
        return new static($id, $alias);
    }

    public function marshall() {
        return [
            "version" => $this->version,
            "cache_key" => $this->cache_key,
            "request_alias" => $this->request_alias,
            "request_id" => $this->request_id,
            "is_dealer" => $this->dealer,
            "colors" => $this->product->colors->marshall(),
            "sizes" => $this->parsed_sized->marshall(),
            "price" => $this->dealer ? $this->product->gross : $this->product->retail,
            "old_price" => $this->dealer ? $this->product->gross_old : $this->product->retail_old,
            "discount" => $this->dealer ? $this->product->discount_gross : $this->product->discount_retail,
            "name" => $this->product->name,
            "article" => $this->product->safe_article,
            "description" => $this->product->description,
            "consists" => $this->product->consists,
            "alias" => $this->product->alias,
            "image" => $this->product->default_image,
            "id" => $this->product->id,
        ];
    }

}
