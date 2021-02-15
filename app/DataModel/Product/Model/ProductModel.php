<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Model;

/**
 * @property string $key
 * @property integer $id
 * @property string $guid
 * @property string $alias
 * @property string $article
 * @property string $meta_title
 * @property string $meta_description
 * @property string $og_title
 * @property string $og_description
 * @property string $meta_keywords
 * @property string $description
 * @property string $consists
 * @property string $name
 * @property bool $enabled
 * @property bool $orderable
 * @property bool $html_mode_d
 * @property bool $html_mode_c
 * @property ProductCatalogCollection $catalogs
 * @property ProductColorCollection $colors
 * @property ProductSizeCollection $sizes
 * @property ProductCrossCollection $cross
 * @property double $retail
 * @property double $gross
 * @property double $retail_old
 * @property double $gross_old
 * @property double $discount_retail
 * @property double $discount_gross
 * @property string $default_image
 * @property int $sort
 * @property PropertyCollection $properties
 * @property bool $has_colors
 * @property bool $has_sizes
 * @property bool $has_crosses
 * @property bool $has_cross
 * @property string $source_article
 * @property string $safe_article
 * @property double $safe_gross  gross если есть, иначе - ретайл
 * @property double $safe_retail retail если есть,иначе - гросс
 */
class ProductModel implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    const CACHE_BEAKON_DEP = 'product';
    const SKIP_LOAD_ID = -999;
    const QUERY_TAG_AFTER_FIELDS = "AFTER_FIELDS";
    const QUERY_TAG_BEFORE_FROM = "BEFORE_FROM";
    const QUERY_TAG_BEFORE_FIRST_TABLE = "BEFORE_FIRST_TABLE";
    const QUERY_TAG_AFTER_FIRST_TABLE = "AFTER_FIRST_TABLE";
    const QUERY_TAG_AFTER_LAST_JOIN = "AFTER_LAST_JOIN";
    const QUERY_TAG_WHERE_PLACE = "WHERE_PLACE";
    const QUERY_TAG_ORDER_PLACE = "ORDER_PLACE";
    const QUERY_TAG_LIMIT_PLACE = "LIMIT_PLACE";
    const MEDIA_CONTEXT = "product";

    //<editor-fold defaultstate="collapsed" desc="props">
    protected $skip_load = false;

    /** @var integer */
    protected $id;

    /** @var string */
    protected $guid;

    /** @var string */
    protected $alias;

    /** @var string */
    protected $article;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $enabled;

    /** @var bool */
    protected $html_mode_c;

    /** @var bool */
    protected $html_mode_d;

    /** @var bool */
    protected $orderable;

    /** @var ProductCatalogCollection */
    protected $catalogs;

    /** @var ProductCrossCollection */
    protected $cross;

    /** @var ProductColorCollection */
    protected $colors;

    /** @var ProductSizeCollection */
    protected $sizes;

    /** @var string */
    protected $meta_title;

    /** @var string */
    protected $meta_description;

    /** @var string */
    protected $meta_keywords;

    /** @var string */
    protected $og_title;

    /** @var string */
    protected $og_description;

    /** @var string */
    protected $description;

    /** @var string */
    protected $consists;

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
    protected $default_image;

    /** @var int */
    protected $sort;

    /** @var PropertyCollection */
    protected $properties;

    /** @var string */
    protected $source_article;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    protected function __get__key() {
        return "P{$this->id}";
    }

    /** @return integer */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__guid() {
        return $this->guid;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    /** @return string */
    protected function __get__article() {
        return $this->article;
    }

    /** @return string */
    protected function __get__meta_title() {
        return $this->meta_title;
    }

    /** @return string */
    protected function __get__meta_description() {
        return $this->meta_description;
    }

    /** @return string */
    protected function __get__meta_keywords() {
        return $this->meta_keywords;
    }

    /** @return string */
    protected function __get__og_title() {
        return $this->og_title;
    }

    /** @return string */
    protected function __get__og_description() {
        return $this->og_description;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return bool */
    protected function __get__html_mode_c() {
        return $this->html_mode_c;
    }

    /** @return bool */
    protected function __get__html_mode_d() {
        return $this->html_mode_d;
    }

    /** @return bool */
    protected function __get__orderable() {
        return $this->orderable;
    }

    /** @return ProductCatalogCollection */
    protected function __get__catalogs() {
        return $this->catalogs;
    }

    /** @return ProductCrossCollection */
    protected function __get__cross() {
        return $this->cross;
    }

    /** @return ProductSizeCollection */
    protected function __get__sizes() {
        return $this->sizes;
    }

    /** @return ProductColorCollection */
    protected function __get__colors() {
        return $this->colors;
    }

    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__description() {
        return $this->description;
    }

    /** @return string */
    protected function __get__consists() {
        return $this->consists;
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
    protected function __get__default_image() {
        return $this->default_image;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    protected function __get__properties() {
        return $this->properties;
    }

    /** @return bool */
    protected function __get__has_colors() {
        return !!($this->colors && !$this->colors->empty);
    }

    /** @return bool */
    protected function __get__has_sizes() {
        return !!($this->sizes && !$this->sizes->empty);
    }

    protected function __get__has_crosses() {
        return ($this->cross && $this->cross->count) ? true : false;
    }

    protected function __get__has_cross() {
        return ($this->cross && $this->cross->count) ? true : false;
    }

    /** @return string */
    protected function __get__source_article() {
        return $this->source_article;
    }

    /** @return string */
    protected function __get__safe_article() {
        return $this->source_article ? $this->source_article : $this->article;
    }

    protected function __get__safe_gross() {
        return floatval(null === $this->gross ? $this->retail : $this->gross);
    }

    protected function __get__safe_retail() {
        return floatval(null === $this->retail ? $this->gross : $this->retail);
    }

    //</editor-fold>

    protected function __construct(int $id, bool $skip_load = false) {
        $this->skip_load = $skip_load;
        if (!$skip_load) {
            $this->load($id);
        }
    }

    protected static function get_main_query() {
        return "SELECT A.*,B.name,B.description,B.consists,
            C.title meta_title,C.keywords meta_keywords,C.description meta_description,C.og_title,C.og_description,
            P.retail,P.gross,P.retail_old,P.gross_old,P.discount_retail,P.discount_gross
            /*--PLACEHOLDER:AFTER_FIELDS*/
            /*--PLACEHOLDER:BEFORE_FROM*/
            FROM  
            /*--PLACEHOLDER:BEFORE_FIRST_TABLE*/
            catalog__product A /*--PLACEHOLDER:AFTER_FIRST_TABLE*/
            LEFT JOIN catalog__product__strings B ON(A.id=B.id)
            LEFT JOIN catalog__product__meta C ON(A.id=C.id)
            LEFT JOIN catalog__product__price P ON(P.id=A.id) 
            /*--PLACEHOLDER:AFTER_LAST_JOIN*/
            /*--PLACEHOLDER:WHERE_PLACE*/
            /*--PLACEHOLDER:ORDER_PLACE*/
            /*--PLACEHOLDER:LIMIT_PLACE*/            
            ";
    }

    protected static function clear_query($query) {
        return preg_replace("/\/\*--PLACEHOLDER:[^\*]{0,}\*\//i", '', $query);
    }

    protected static function process_query_tag(string $query, string $tag, string $rep) {
        $upper_tag = mb_strtoupper($tag, 'UTF-8');
        $search = "/*--PLACEHOLDER:{$upper_tag}*/";
        return str_replace($search, "{$rep} {$search}", $query);
    }

    protected static function process_query_tags(string $query, array $tags = [], $clear = true) {
        foreach ($tags as $tag_key => $tag_rep) {
            $query = static::process_query_tag($query, $tag_key, $tag_rep);
        }
        if ($clear) {
            return static::clear_query($query);
        }
        return $query;
    }

    /**
     * 
     * @param int $id
     * @return $this
     */
    protected function load(int $id) {
        $query = static::process_query_tags(static::get_main_query(), [
                    static::QUERY_TAG_WHERE_PLACE => "WHERE A.id=:Pid",
        ]);
        //\Out\Out::F()->add('debug_query', $query);
        // $query = static::get_main_query() . "WHERE A.id=:Pid";
        $row = \DB\DB::F()->queryRow($query, [":Pid" => $id]);
        $row ? FALSE : ProductNotFoundError::R("not found");
        $this->import_props($row);
        return $this;
    }

    /**
     * 
     * @param int $id
     * @return $this
     */
    protected function load_alias(string $alias) {
        $query = static::process_query_tags(static::get_main_query(), [
                    static::QUERY_TAG_WHERE_PLACE => "WHERE A.alias=:Palias",
        ]);
        $row = \DB\DB::F()->queryRow($query, [":Palias" => $alias]);
        $row ? FALSE : ProductNotFoundError::R("not found");
        $this->import_props($row);
        return $this;
    }

    protected function t_common_import_get_filters() {
        return [
            'id' => ['IntMore0'], //integer
            'guid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'alias' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'article' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'source_article' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'meta_title' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'meta_description' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'og_title' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'og_description' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'meta_keywords' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'enabled' => ['Boolean', 'DefaultTrue'], //bool
            'html_mode_c' => ['Boolean', 'DefaultTrue'], //bool
            'html_mode_d' => ['Boolean', 'DefaultTrue'], //bool
            'orderable' => ['Boolean', 'DefaultTrue'], //bool
            'description' => ['Trim', 'NEString', 'DefaultNull'], //string
            'consists' => ['Trim', 'NEString', 'DefaultNull'], //string
            'retail' => ['Float', 'DefaultNull'],
            'gross' => ['Float', 'DefaultNull'],
            'retail_old' => ['Float', 'DefaultNull'],
            'gross_old' => ['Float', 'DefaultNull'],
            'discount_retail' => ['Float', 'DefaultNull'],
            'discount_gross' => ['Float', 'DefaultNull'],
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'sort' => ['Int', 'Default0'],
        ];
    }

    protected function t_common_import_after_import() {
        $this->colors = ProductColorCollection::F($this->id, $this->skip_load);
        $this->sizes = ProductSizeCollection::F($this->id, $this->skip_load);
        $this->catalogs = ProductCatalogCollection::F($this->id, $this->skip_load);
        $this->cross = ProductCrossCollection::F($this->id, $this->skip_load);
        $this->properties = PropertyCollection::F();
        if (!$this->skip_load) {
            $this->properties->load_from_database($this->id);
        }
        return $this;
    }

    protected function load_parts() {
        $this->colors = ProductColorCollection::F($this->id);
        $this->sizes = ProductSizeCollection::F($this->id);
        $this->catalogs = ProductCatalogCollection::F($this->id);
        $this->properties->load_from_database($this->id);
        $this->cross = ProductCrossCollection::F($this->id);
    }

    public static function F(int $id) {
        try {
            return new static($id);
        } catch (ProductNotFoundError $e) {
            
        }
        return null;
    }

    public static function FA(string $alias) {
        try {
            $val = new static(0, true);
            $val->load_alias($alias);
            $val->load_parts();
            return $val;
        } catch (ProductNotFoundError $e) {
            
        }
        return null;
    }

    public static function RESET_CACHE() {
        \Cache\FileBeaconDependency::F(static::CACHE_BEAKON_DEP)->reset_dependency_beacons();
    }

    /**
     * массовая загрузка моделей по фильтру-джойну с опорной таблицей
     * @param string $table_name   имя опорной таблицы
     * @param int $limit     макс количество для загрузки, 0 - грузить вообще все
     * @param string $sort - сортировка. Одно из значений SM__XXX
     * @param int $offset offset
     * @return ProductModel[]
     */
    public static function load_join(string $table_name, int $limit = 24, string $sort = null, int $offset = 0) {
        $extra_sort = IProductSortMode::MODES[IProductSortMode::SM_DEFAULT];
        if ($sort && array_key_exists($sort, IProductSortMode::MODES)) {
            $extra_sort = IProductSortMode::MODES[$sort];
        }
        $offset = \Filters\FilterManager::F()->apply_chain($offset, ['IntMore0', 'Default0']);
        $query = static::process_query_tags(static::get_main_query(), [
                    static::QUERY_TAG_BEFORE_FIRST_TABLE => "`{$table_name}` SPT JOIN ",
                    static::QUERY_TAG_AFTER_FIRST_TABLE => " ON (A.id=SPT.id) ",
                    static::QUERY_TAG_ORDER_PLACE => " ORDER BY {$extra_sort}",
                    static::QUERY_TAG_LIMIT_PLACE => ($limit && $limit > 0 ? (" LIMIT {$limit} OFFSET {$offset} ") : ""),
        ]);
        $raw_rows = \DB\DB::F()->queryAll($query);
        $loaded_items = [];
        $loaded_ids = [];
        foreach ($raw_rows as $row) {
            $product = new static(0, true);
            $product->import_props($row);
            $loaded_items[] = $product;
            $loaded_ids[] = $product->id;
        }

        if (count($loaded_items)) {
            // инициализация времянки для загрузки дочерних элементов
            $tn = "a" . md5(__METHOD__);
            $tt_query = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
                CREATE TEMPORARY TABLE `{$tn}` (id INT(11) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MEMORY;
                INSERT INTO `{$tn}` (id) VALUES(" . implode("),(", $loaded_ids) . ") ON DUPLICATE KEY UPDATE id=VALUES(id);
            ";
            \DB\DB::F()->exec($tt_query);
            // инициализировать наборы цветов
            $colors_all = ProductColorCollection::load_join($tn);
            // инициализировать наборы размеров
            $sizes_all = ProductSizeCollection::load_join($tn);
            // инициализировать наборы каталогов
            $catalogs_all = ProductCatalogCollection::load_join($tn);
            // инициализировать наборы свойств
            $properties_all = PropertyCollection::load_join($tn);
            //init cross collection
            $crosses_all = ProductCrossCollection::load_join($tn);
            foreach ($loaded_items as $product) {/* @var $product static */
                $product->colors->import_colors_raw($colors_all);
                $product->sizes->import_raw($sizes_all);
                $product->catalogs->import_raw($catalogs_all);
                $product->properties->import_raw($properties_all, $product->id);
                $product->cross->import_raw($crosses_all);
            }
        }
        return $loaded_items;
    }

    /**
     * 
     * @param int[] $ids
     * @return ProductModel[]
     */
    public static function load_array(array $ids) {
        
        $ids = \Filters\FilterManager::F()->apply_chain($ids, ["ArrayOfInt", "DefaultNull"], \Filters\params\ArrayParamBuilder::B(["ArrayOfInt" => ["count_min" => 0, 'more' => 0]], true)->get_param_set_for_property());
        if (!$ids) {
            return [];
        }
        $tn = "a" . md5(__METHOD__);
        $query = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
            CREATE TEMPORARY TABLE `{$tn}`(id INT(11) UNSIGNED NOT NULL,PRIMARY KEY(id));
            INSERT INTO `{$tn}` (id) VALUES(" . implode("),(", $ids) . ") ON DUPLICATE KEY UPDATE id=VALUES(id);";
        \DB\DB::F()->exec($query);
        \DB\errors\MySQLWarn::F(\DB\DB::F());
        return static::load_join($tn, -1, null);
    }

}
