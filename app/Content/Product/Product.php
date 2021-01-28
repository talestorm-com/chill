<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Product;

/**
 * @property string $product_alias
 * @property int $product_id
 * @property \DataModel\Product\Model\ProductModel $product
 * @property \Content\common_classes\Breadcrumb[] $breadcrumbs
 * @property string $version
 * @property bool $dealer
 * @property \Content\IImageCollection $images
 * @property string $images_as_json
 * @property SizeCollection $sizes
 * @property bool $has_sizes
 * @property bool $has_alter_sizes
 * @property bool $product_has_description
 * @property bool $product_has_consists
 * 
 */
class Product extends \Content\Content implements \Content\IImageSupport, \Out\Metadata\IMetadataSupport {

    //<editor-fold defaultstate="collapsed" desc="props && accessors">
    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $product_alias;

    /** @var int */
    protected $product_id;

    /** @var \DataModel\Product\Model\ProductModel */
    protected $product;

    /** @var \Content\common_classes\Breadcrumb[] */
    protected $breadcrumbs;

    /** @var string */
    protected $version;

    /** @var bool */
    protected $dealer;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var SizeCollection */
    protected $sizes;

    /** @var bool */
    protected $product_has_description;

    /** @var bool */
    protected $product_has_consists;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__product_alias() {

        return $this->product_alias;
    }

    /** @return int */
    protected function __get__product_id() {
        return $this->product_id;
    }

    /** @return \DataModel\Product\Model\ProductModel */
    protected function __get__product() {
        return $this->product;
    }

    /** @return \Content\common_classes\Breadcrumb[] */
    protected function __get__breadcrumbs() {
        return $this->breadcrumbs;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    /** @return bool */
    protected function __get__dealer() {
        return $this->dealer;
    }

    /** @return \Content\IImageCollection */
    protected function __get__images() {
        return $this->images;
    }

    protected function __get__images_as_json() {
        $x = $this->images && count($this->images) ? $this->images->marshall() : [
            ["context" => "fallback", "owner_id" => "1", "image" => "product"]
        ];
        return json_encode((is_array($x) ? $x : []));
    }

    /** @return SizeCollection */
    protected function __get__sizes() {
        return $this->sizes;
    }

    protected function __get__has_sizes() {
        return $this->sizes->has_sizes;
    }

    protected function __get__has_alter_sizes() {
        return $this->sizes->has_alters;
    }

    /** @return bool */
    protected function __get__product_has_description() {
        return $this->product_has_description;
    }

    /** @return bool */
    protected function __get__product_has_consists() {
        return $this->product_has_consists;
    }

    //</editor-fold>
    //</editor-fold>

    protected static function dealer_mode() {
        if (\Auth\Auth::F()->is_authentificated()) {
            if (\Auth\Auth::F()->get_user_info()->is_dealer) {
                if (\Auth\Auth::F()->is(\Auth\Roles\RoleDealer::class)) {
                    return true;
                }
            }
        }
        return false;
    }

    protected static function get_file_ver() {
        return md5(implode("|", [__FILE__, filemtime(__FILE__)]));
    }

    protected static function cache_key(string $alias) {
        return implode("|", [__CLASS__, mb_strtolower($alias, 'UTF-8'), static::dealer_mode() ? "1" : "0"]);
    }

    protected function __construct(string $alias) {
        $this->version = static::get_file_ver();
        $this->dealer = static::dealer_mode();
        $this->load($alias);
        $this->set_cache();
    }

    /**
     * 
     * @param string $alias
     * @return \Content\Product\Product
     */
    protected function load(string $alias): Product {
        $this->product = \DataModel\Product\Model\ProductModel::FA($alias);
        if (!$this->product) {
            \Router\NotFoundError::R("not found");
        }
        $this->product_alias = $this->product->alias;
        $this->product_id = $this->product->id;
        $this->init_breadcrumbs();
        $this->images = \Content\DefaultImageCollection::F(\DataModel\Product\Model\ProductModel::MEDIA_CONTEXT, $this->product_id);
        $this->sizes = SizeCollection::F($this->product->sizes);
        $this->product_has_description = mb_strlen(trim(strip_tags($this->product->description)), 'UTF-8') ? true : false;
        $this->product_has_consists = mb_strlen(trim(strip_tags($this->product->consists)), "UTF-8") ? true : false;
        return $this;
    }

    /**
     * 
     * @return \Content\Product\Product
     */
    protected function init_breadcrumbs(): Product {
        $tree = \CatalogTree\CatalogTreeSinglet::F();
        $breadcrumb_variants = [];
        foreach ($this->product->catalogs as $catalog_entry) {/* @var $catalog_entry \DataModel\Product\Model\ProductCatalog */
            $catalog_node = $tree->tree->get_item_by_id($catalog_entry->id); /* @var $catalog_node \CatalogTree\CatalogTreeItem */
            if ($catalog_node && $catalog_node->visible_parents) {
                $breadcrumb_variants[] = $this->create_breadcrumb_chain($catalog_node);
            }
        }
        usort($breadcrumb_variants, function(array $a, array $b) {
            return count($a) - count($b);
        });
        $selected_breadcrumbs = count($breadcrumb_variants) ? $breadcrumb_variants[0] : null;
        $this->breadcrumbs = [];
        if ($selected_breadcrumbs) {
            $selected_breadcrumbs[] = new \Content\common_classes\Breadcrumb("Каталог", "/");
            $selected_breadcrumbs = array_reverse($selected_breadcrumbs);
            $selected_breadcrumbs[] = new \Content\common_classes\Breadcrumb($this->product->name, "/product/{$this->product->alias}");
            $this->breadcrumbs = $selected_breadcrumbs;
        }
        return $this;
    }

    /**
     * returns <b>REVERSED</b> breadcrumbs. does <b>not contain</b> first and last items!
     * @param \CatalogTree\CatalogTreeItem $node
     * @return \Content\common_classes\Breadcrumb[]
     */
    protected function create_breadcrumb_chain(\CatalogTree\CatalogTreeItem $node) {
        $result = [];
        $n = $node; /* @var $n \CatalogTree\CatalogTreeItem */
        while ($n && !$n->terminal) {
            $result[] = new \Content\common_classes\Breadcrumb($n->name, "/catalog/{$n->alias}");
            $n = $n->parent;
        }
        return $result;
    }

    /**
     * 
     * @return \Content\Product\Product
     */
    protected function set_cache(): Product {
        $cache_key = static::cache_key($this->product_alias);
        $cache = \Cache\FileCache::F();
        $cache->put($cache_key, $this, 0, \Cache\FileBeaconDependency::F(implode(",", [
                    \DataModel\Product\Model\ProductModel::CACHE_BEAKON_DEP,
                    \CatalogTree\CatalogTree::CACHE_BEAKON_DEPENDENCY,
                    \DataModel\CatalogSizeDef\CatalogSizeDefVoc::CACHE_DEPENDENCY,
        ])));
        return $this;
    }

    /**
     * 
     * @param string $alias
     * @return \Content\Product\Product
     */
    public static function F(string $alias): Product {
        return new static($alias);
    }

    /**
     * 
     * @param string $alias
     * @return \Content\Product\Product
     */
    public static function C(string $alias): Product {
        $cs = static::class;
        $cache_key = static::cache_key($alias);
        $item = \Cache\FileCache::F()->get($cache_key); /* @var $item static */
        if ($item && is_object($item) && ($item instanceof $cs) && $item->version === static::get_file_ver()) {
            return $item;
        }
        return new static($alias);
    }

    public function get_has_images(): bool {
        return $this->images->get_has_images();
    }

    public function get_images_count(): int {
        return $this->images->get_images_count();
    }

    public function get_object_images(): \Content\IImageCollection {
        return $this->images;
    }

    public function meta_get_description(): string {
        $result = \Filters\FilterManager::F()->apply_chain($this->product->meta_description, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$result) {
            $result = \Filters\FilterManager::F()->apply_chain($this->product->description, ["Strip", "Trim", "NEString", "DefaultNull"]);
        }
        return (string) $result;
    }

    public function meta_get_keywords(): string {
        $result = \Filters\FilterManager::F()->apply_chain($this->product->meta_keywords, ["Strip", "Trim", "NEString", "DefaultNull"]);
        return (string) $result;
    }

    public function meta_get_og_description(): string {
        $result = \Filters\FilterManager::F()->apply_chain($this->product->og_description, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$result) {
            $result = $this->meta_get_description();
        }
        return (string) $result;
    }

    public function meta_get_og_image_context(): string {
        return (string) \DataModel\Product\Model\ProductModel::MEDIA_CONTEXT;
    }

    public function meta_get_og_image_image(): string {
        return (string) $this->product->default_image;
    }

    public function meta_get_og_image_owner(): string {
        return (string) $this->product->id;
    }

    public function meta_get_og_image_support(): bool {
        return true;
    }

    public function meta_get_og_support(): bool {
        return true;
    }

    public function meta_get_og_title(): string {
        $result = \Filters\FilterManager::F()->apply_chain($this->product->og_title, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$result) {
            $result = $this->meta_get_title();
        }
        return (string) $result;
    }

    public function meta_get_title(): string {
        $result = \Filters\FilterManager::F()->apply_chain($this->product->meta_title, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$result) {
            $result = \Filters\FilterManager::F()->apply_chain($this->product->name, ["Strip", "Trim", "NEString", "DefaultNull"]);
        }
        return (string) $result;
    }

    public function is_product_favorite() {
        if (\Auth\Auth::F()->is_authentificated()) {
            $r = \Filters\FilterManager::F()->apply_chain(\DB\DB::F()->queryScalar("SELECT product_id FROM user__favorite WHERE user_id=:P and product_id=:PP", [":P" => \Auth\Auth::F()->get_id(), ":PP" => $this->product_id]), ["IntMore0", 'Default0']);
            return $r ? true : false;
        }
        return false;
    }

}
