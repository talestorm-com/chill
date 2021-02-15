<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile;

/**
 * 
 * @property int $id
 * @property string $alias
 * @property string $title
 * @property string $info
 * @property string $loader
 * @property string $template
 * @property bool $visible
 * @property bool $crop
 * @property bool $crop_fill
 * @property string $background
 * @property \Content\IImageCollection  $images
 * @property CatalogCollection $catalogs
 * @property PropertyCollection  $properties   
 * @property string $version
 * @property string background_url
 * @property bool $show_header
 * @property string $css_class
 * @property bool $ignore_product_visibility
 * @property bool $ignore_catalog_visibility
 */
class CatalogTile implements \common_accessors\IMarshall, \Content\IImageSupport {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    const CACHE_BEACON = "catalog_tile";
    const MEDIA_CONTEXT = "catalog_tile";

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $id;

    /** @var string */
    protected $alias;

    /** @var string */
    protected $title;

    /** @var string */
    protected $info;

    /** @var string */
    protected $loader;

    /** @var string */
    protected $template;

    /** @var bool */
    protected $visible;

    /** @var bool */
    protected $crop;

    /** @var bool */
    protected $crop_fill;

    /** @var string */
    protected $background;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var CatalogCollection */
    protected $catalogs;

    /** @var PropertyCollection */
    protected $properties;

    /** @var string */
    protected $version;

    /** @var bool */
    protected $show_header;

    /** @var string */
    protected $css_class;

    /** @var bool */
    protected $ignore_product_visibility;

    /** @var bool */
    protected $ignore_catalog_visibility;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    /** @return string */
    protected function __get__title() {
        return $this->title;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return string */
    protected function __get__loader() {
        return $this->loader;
    }

    /** @return string */
    protected function __get__template() {
        return $this->template;
    }

    /** @return bool */
    protected function __get__visible() {
        return $this->visible;
    }

    /** @return bool */
    protected function __get__crop() {
        return $this->crop;
    }

    /** @return bool */
    protected function __get__crop_fill() {
        return $this->crop_fill;
    }

    /** @return string */
    protected function __get__background() {
        return $this->background;
    }

    /** @return \Content\IImageCollection */
    protected function __get__images() {
        return $this->images;
    }

    /** @return CatalogCollection */
    protected function __get__catalogs() {
        return $this->catalogs;
    }

    /** @return PropertyCollection */
    protected function __get__properties() {
        return $this->properties;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    protected function __get__background_url() {
        return $this->background ? trim($this->background, "#") : null;
    }

    /** @return bool */
    protected function __get__show_header() {
        return $this->show_header;
    }

    /** @return string */
    protected function __get__css_class() {
        return $this->properties?$this->properties->get_filtered("css_class",['Strip','Trim','NEString','DefaultEmptyString']):'';
        //return $this->css_class;
    }

    /** @return bool */
    protected function __get__ignore_product_visibility() {
        return $this->ignore_product_visibility;
    }

    /** @return bool */
    protected function __get__ignore_catalog_visibility() {
        return $this->ignore_catalog_visibility;
    }

    //</editor-fold>


    protected function __construct() {
        $this->images = \Content\DefaultImageCollection::F(static::MEDIA_CONTEXT);
        $this->properties = PropertyCollection::F();
        $this->catalogs = CatalogCollection::F();
        $this->version = static::get_file_ver();
    }

    protected function load_id(int $id) {
        $query = "SELECT * FROM catalog__tile WHERE id=:P";
        $params = [":P" => $id];
        $this->load_by_query($query, $params);
    }

    protected function load_alias(string $alias) {
        $query = "SELECT * FROM catalog__tile WHERE alias=:P";
        $params = [":P" => $alias];
        $this->load_by_query($query, $params);
    }

    protected function load_by_query(string $query, array $props) {
        $row = \DB\DB::F()->queryRow($query, $props);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
        if ($this->id) {
            $this->images->load(static::MEDIA_CONTEXT, (string) $this->id);
            $this->properties->load_from_database($this->id);
            $this->catalogs->load($this->id);
        }
    }

    protected function t_common_import_get_filters() {
        return [
            "id" => ["IntMore0", "DefaultNull"], //int
            "alias" => ["Strip", 'Trim', 'NEString', "DefaultNull"], //string
            "title" => ["Strip", 'Trim', 'NEString', "DefaultNull"], //string
            "info" => ["Strip", 'Trim', 'NEString', 'DefaultEmptyString'], //string
            "loader" => ["Strip", 'Trim', 'NEString', "DefaultNull"], //string
            "template" => ["Strip", 'Trim', 'NEString', "DefaultNull"], //string
            "visible" => ["Boolean", "DefaultTrue"], //bool
            "crop" => ["Boolean", "DefaultTrue"], //bool
            "crop_fill" => ["Boolean", "DefaultFalse"], //bool
            "background" => ["Strip", 'Trim', 'NEString', "DefaultNull"], //string   
            "show_header" => ["Boolean", "DefaultTrue"], //bool
            "css_class" => ["Strip", 'Trim', 'NEString', "DefaultEmptyString"], //string
            "ignore_product_visibility" => ["Boolean", "DefaultFalse"], //bool
            "ignore_catalog_visibility" => ["Boolean", "DefaultFalse"], //bool
        ];
    }

    protected static function get_file_ver() {
        return md5(implode("", [__FILE__, filemtime(__FILE__)]));
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

    /**
     * 
     * @param int $id
     * @return \Content\CatalogTile\CatalogTile
     */
    public static function F(int $id): CatalogTile {
        $item = new static();
        $item->load_id($id);
        return $item;
    }

    /**
     * 
     * @param string $alias
     * @return \Content\CatalogTile\CatalogTile
     */
    public static function FA(string $alias): CatalogTile {
        $item = new static();
        $item->load_alias($alias);
        return $item;
    }

    /**
     * 
     * @param string $alias
     * @return \Content\CatalogTile\CatalogTile
     */
    public static function C(string $alias): CatalogTile {
        $cache_key = implode("", [__CLASS__, $alias]);
        $cache = \Cache\FileCache::F();
        $item = $cache->get($cache_key); /* @var $item self */
        $cs = static::class;
        if ($item && is_object($item) && ($item instanceof $cs) && $item->version === static::get_file_ver()) {
            return $item;
        }
        $item = static::FA($alias);
        $cache->put($cache_key, $item, 0, \Cache\FileBeaconDependency::F(implode(",", [static::CACHE_BEACON, "front_catalog", "product"])));
        return $item;
    }

    public static function clear_dependency_beacon() {
        \Cache\FileBeaconDependency::F(static::CACHE_BEACON)->reset_dependency_beacons();
    }

}
