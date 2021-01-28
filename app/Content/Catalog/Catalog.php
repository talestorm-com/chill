<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Catalog;

/**
 * 
 * обертка рендер/лоадер для каталога
 * @property string $catalog_alias
 * @property int $catalog_id
 * @property \CatalogTree\CatalogTreeItem $node
 * @property PropertyCollection $properties
 * @property \DataModel\Product\Model\ProductModel[] $products
 * @property CatalogLoadParams $load_params
 * @property bool $is_last_page
 * @property int $total
 * @property string $version
 * @property boolean $is_small_mode_active
 * @property boolean $is_big_mode_active
 * @property \Content\common_classes\Breadcrumb[] $breadcrumbs
 * @property CatalogMeta $meta
 * @property int $requested_perpage
 * @note filters hash?
 */
class Catalog extends \Content\Content implements \Out\Metadata\IMetadataSupport {

    const SORT_OVERRIDE = [
        "DISCOUNT0" => \DataModel\Product\Model\IProductSortMode::SM_DISCOUNT_RETAIL,
        "DISCOUNT1" => \DataModel\Product\Model\IProductSortMode::SM_DISCOUNT_GROSS,
        "DISCOUNT_REV0" => \DataModel\Product\Model\IProductSortMode::SM_DISCOUNT_RETAIL_REV,
        "DISCOUNT_REV1" => \DataModel\Product\Model\IProductSortMode::SM_DISCOUNT_GROSS_REV,
        "PRICE0" => \DataModel\Product\Model\IProductSortMode::SM_PRICE_RETAIL,
        "PRICE1" => \DataModel\Product\Model\IProductSortMode::SM_PRICE_GROSS,
        "PRICE_REV0" => \DataModel\Product\Model\IProductSortMode::SM_PRICE_RETAIL_REV,
        "PRICE_REV1" => \DataModel\Product\Model\IProductSortMode::SM_PRICE_GROSS_REV,
    ];

    //<editor-fold defaultstate="collapsed" desc="propg && getters">
    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $catalog_alias;

    /** @var int */
    protected $catalog_id;

    /** @var \CatalogTree\CatalogTreeItem */
    protected $node;

    /** @var PropertyCollection */
    protected $properties;

    /** @var \DataModel\Product\Model\ProductModel[] */
    protected $products;

    /** @var CatalogLoadParams */
    protected $load_params;

    /** @var bool */
    protected $is_last_page;

    /** @var int */
    protected $total;

    /** @var string */
    protected $version;

    /** @var \Content\common_classes\Breadcrumb[] */
    protected $breadcrumbs;

    /** @var CatalogMeta */
    protected $meta;
    
    /** @var int */
    protected $requested_perpage;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__catalog_alias() {
        return $this->catalog_alias;
    }

    /** @return int */
    protected function __get__catalog_id() {
        return $this->catalog_id;
    }

    /** @return \CatalogTree\CatalogTreeItem */
    protected function __get__node() {
        return $this->node;
    }

    /** @return PropertyCollection */
    protected function __get__properties() {
        return $this->properties;
    }

    /** @return \DataModel\Product\Model\ProductModel[] */
    protected function __get__products() {
        return $this->products;
    }

    /** @return CatalogLoadParams */
    protected function __get__load_params() {
        return $this->load_params;
    }

    /** @return bool */
    protected function __get__is_last_page() {
        return $this->is_last_page;
    }

    /** @return int */
    protected function __get__total() {
        return $this->total;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    /** @return boolean */
    protected function __get__is_small_mode_active() {
        return !\DataMap\CookieDataMap::F()->get_filtered("catalog_view_large", ['Boolean', 'DefaultTrue']);
    }

    /** @return boolean */
    protected function __get__is_big_mode_active() {
        return \DataMap\CookieDataMap::F()->get_filtered("catalog_view_large", ['Boolean', 'DefaultTrue']);
    }

    /** @return \Content\common_classes\Breadcrumb[] */
    protected function __get__breadcrumbs() {
        return $this->breadcrumbs;
    }

    /** @return CatalogMeta */
    protected function __get__meta() {
        return $this->meta;
    }
    
    protected function __get__requested_perpage(){
        return $this->requested_perpage;
    }

    //</editor-fold>
    //</editor-fold>

    /**
     * 
     * @param \CatalogTree\CatalogTreeItem $node
     * @param \Content\Catalog\CatalogLoadParams $params
     * @param bool $disable_cache
     */
    protected function __construct(\CatalogTree\CatalogTreeItem $node, CatalogLoadParams $params, bool $disable_cache = false,$requested_perpage=null) {
        $this->catalog_alias = $node->alias;
        $this->catalog_id = $node->id;
        $this->node = $node;
        $this->properties = PropertyCollection::F()->load_from_database($this->catalog_id);
        $this->load_params = $params;
        $this->requested_perpage = $requested_perpage?$requested_perpage:$this->load_params->per_page;
        $this->version = static::get_file_ver();
        $this->meta = CatalogMeta::F($this->catalog_id);
        if ($this->node->visible) {
            $this->load();
            // проверить - если запрос на заведомо большую страницу может и не кешировать
            $this->init_breadcrumbs();
            $disable_cache ? 0 : $this->cache();
        } else {
            $this->items = [];
        }
    }

    protected function init_breadcrumbs() {
        $node = $this->node;
        $result = [];
        while ($node && !$node->terminal) {
            $result[] = new \Content\common_classes\Breadcrumb($node->name, "/Catalog/{$node->alias}");
            $node = $node->parent;
        }
        $result[] = new \Content\common_classes\Breadcrumb("Каталог", "/");
        $this->breadcrumbs = array_reverse($result);
    }

    protected function cache() {
        $cache_key = static::build_cache_key($this->node, $this->load_params);
        \Cache\FileCache::F()->put($cache_key, $this, 0, \Cache\FileBeaconDependency::F(implode(",", [
                    \DataModel\Product\Model\ProductModel::CACHE_BEAKON_DEP,
                    \CatalogTree\CatalogTree::CACHE_BEAKON_DEPENDENCY,
        ])));
    }

    public function __sleep() {
        return ['catalog_id', 'catalog_alias', 'properties', 'products', 'load_params', 'is_last_page', 'total', 'version'];
    }

    public function __wakeup() {
        $this->node = \CatalogTree\CatalogTreeSinglet::F()->tree->get_item_by_id($this->catalog_id);
    }

    public function __node_map(\CatalogTree\CatalogTreeItem $node, \Tree\ITree $tree, $uo = null) {
        if ($node->visible) {
            $uo->i[] = $node->id;
            $node->map([$this, '__node_map'], $tree, $uo);
        }
    }

    protected function load() {
        //неверно. Нужны только видимые каталоги
        $o = new \stdClass();
        $o->i = [$this->catalog_id];
        $this->node->map([$this, '__node_map'], \CatalogTree\CatalogTreeSinglet::F()->tree, $o);
        $tn = "a" . md5(__METHOD__);
        $query = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;DROP TEMPORARY TABLE IF EXISTS `{$tn}p`;
            CREATE TEMPORARY TABLE `{$tn}` (id INT(11) UNSIGNED NOT NULL,PRIMARY KEY (id));
            CREATE TEMPORARY TABLE `{$tn}p` (id INT(11) UNSIGNED NOT NULL,PRIMARY KEY (id));
            INSERT INTO `{$tn}` (id) VALUES(" . implode("),(", $o->i) . ") ON DUPLICATE KEY UPDATE id=VALUES(id);
            INSERT INTO `{$tn}p` (id)
                SELECT P.id FROM `{$tn}` A JOIN catalog__product__group B ON(A.id=B.group_id)
                JOIN catalog__product P ON (P.id=B.product_id)
                WHERE P.enabled=1
            ON DUPLICATE KEY UPDATE `{$tn}p`.id=VALUES(id);";
        \DB\DB::F()->exec($query);
        \DB\errors\MySQLWarn::F(\DB\DB::F());
        $this->products = \DataModel\Product\Model\ProductModel::load_join("{$tn}p", $this->load_params->perpage, $this->get_sort_mode(), $this->load_params->offset);
    }

    protected function get_sort_mode() {
        $sort_mode = \DataModel\Product\Model\IProductSortMode::SM_DEFAULT;
        $psm = $this->properties->get_filtered("sort_mode", ['Trim', 'NEString', 'DefaultNull']);
        if ($psm) {
            $sort_mode = $psm;
            $tmp_sm = "{$sort_mode}" . (($this->load_params->dealer) ? "1" : "0");
            if (array_key_exists($tmp_sm, static::SORT_OVERRIDE)) {
                $sort_mode = static::SORT_OVERRIDE[$tmp_sm];
            }
        }
        $sort_mode = array_key_exists($sort_mode, \DataModel\Product\Model\IProductSortMode::MODES) ? $sort_mode : \DataModel\Product\Model\IProductSortMode::SM_DEFAULT;
        return $sort_mode;
    }

    protected static function build_cache_key(\CatalogTree\CatalogTreeItem $node, \Content\Catalog\CatalogLoadParams $params): string {
        return implode(":", [__CLASS__, implode("|", [
                $node->alias, $params->cache_key
        ])]);
    }

    protected static function get_file_ver(): string {
        return md5(implode("", [__FILE__, filemtime(__FILE__)]));
    }

    /**
     * 
     * @param \CatalogTree\CatalogTreeItem $node
     * @param \Content\Catalog\CatalogLoadParams $params
     * @param bool $disable_cache  do not put result into cache
     * @return \Content\Catalog\Catalog
     */
    public static function F(\CatalogTree\CatalogTreeItem $node, CatalogLoadParams $params, bool $disable_cache = false,int $requested_perpage=null): Catalog {
        return new static($node, $params, $disable_cache,$requested_perpage);
    }

    /**
     * 
     * @param \CatalogTree\CatalogTreeItem $node
     * @param int $page
     * @param int $perpage
     * @param bool $disable_cache  do not put result into cache
     * @return \Content\Catalog\Catalog
     */
    public static function C(\CatalogTree\CatalogTreeItem $node, int $page = 0, int $perpage = 24, bool $disable_cache = false,int $requested_perpage = null): Catalog {
        $catalog_load_params = CatalogLoadParams::F($node->id, $page, $perpage);
        $cache_key = static::build_cache_key($node, $catalog_load_params);
        $cache = \Cache\FileCache::F();
        $item = $cache->get($cache_key); /* @var $item \self */
        $cs = static::class;
        if (false && $item && is_object($item) && ($item instanceof $cs) && $item->version === static::get_file_ver()) {
            return $item;
        }
        return static::F($node, $catalog_load_params, $disable_cache,$requested_perpage);
    }

    public function meta_get_description(): string {
        $result = \Filters\FilterManager::F()->apply_chain($this->meta->meta_description, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$result) {
            $result = \Filters\FilterManager::F()->apply_chain($this->meta->info, ["Strip", "Trim", "NEString", "DefaultNull"]);
        }
        return (string) $result;
    }

    public function meta_get_keywords(): string {
        return (string) \Filters\FilterManager::F()->apply_chain($this->meta->meta_keywords,["Strip", "Trim", "NEString", "DefaultNull"]);
    }

    public function meta_get_og_description(): string {
        $result = \Filters\FilterManager::F()->apply_chain($this->meta->og_description, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$result) {
            $result = $this->meta_get_description();
        }
        return (string) $result;
    }

    public function meta_get_og_image_context(): string {
        return "product_group";
    }

    public function meta_get_og_image_image(): string {
        return (string) $this->node->default_image;
    }

    public function meta_get_og_image_owner(): string {
        return (string) $this->node->id;
    }

    public function meta_get_og_image_support(): bool {
        return true;
    }

    public function meta_get_og_support(): bool {
        return true;
    }

    public function meta_get_og_title(): string {
        $result = \Filters\FilterManager::F()->apply_chain($this->meta->og_title, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$result) {
            $result = $this->meta_get_title();
        }
        return (string) $result;
    }

    public function meta_get_title(): string {
        $result = \Filters\FilterManager::F()->apply_chain($this->meta->meta_title, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$result) {
            $result = $this->node->name;
        }
        return (string) $result;
    }

}
