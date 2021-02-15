<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ClientPackage;

/**
 * Description of Package
 *
 * @author eve
 * @property int $id
 * @property string $name
 * @property double $price
 * @property int $usages
 * @property int $days
 * @property bool $active
 * @property string $defaut_image
 * @property string $version
 * @property \Content\IImageCollection $images
 * @property Properties $properties
 * @property string $formated_price
 * @property bool $valid
 * 
 */
class Package extends \Content\Content implements \Content\IImageSupport {

    use \common_accessors\TCommonImport;

    CONST MEDIA_CONTEXT = 'package';
    CONST CACHE_DEPENDENCY = 'package';

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var double */
    protected $price;

    /** @var int */
    protected $usages;

    /** @var int */
    protected $days;

    /** @var bool */
    protected $active;

    /** @var string */
    protected $defaut_image;

    /** @var string */
    protected $version;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var Properties */
    protected $properties;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return double */
    protected function __get__price() {
        return $this->price;
    }

    /** @return int */
    protected function __get__usages() {
        return $this->usages;
    }

    /** @return int */
    protected function __get__days() {
        return $this->days;
    }

    /** @return bool */
    protected function __get__active() {
        return $this->active;
    }

    /** @return string */
    protected function __get__defaut_image() {
        return $this->defaut_image;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    /** @return \Content\IImageCollection */
    protected function __get__images() {
        return $this->images;
    }

    /** @return Properties */
    protected function __get__properties() {
        return $this->properties;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->name && $this->price > 0 && $this->days > 0 && $this->usages > 0;
    }

    /** @return string */
    protected function __get__formated_price() {
        return number_format($this->price, 2, '.', ' ');
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="IImageSupport delegate">    
    public function get_has_images(): bool {
        return $this->images->get_has_images();
    }

    public function get_images_count(): int {
        return $this->images->get_images_count();
    }

    public function get_object_images(): \Content\IImageCollection {
        return $this->images;
    }

    //</editor-fold>

    public function __construct(int $id = null) {
        \ImageFly\MediaContextInfo::register_media_context(static::MEDIA_CONTEXT, 2048, 2048, 10, 10);
        $this->version = static::get_class_version();
        $this->images = \Content\DefaultImageCollection::F();
        $this->properties = Properies::F();
        if ($id) {
            $this->load($id);
        }
    }

    public function load(int $id) {
        $query = "SELECT * FROM fitness__package WHERE id=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $row && is_array($row) ? 0 : \Router\NotFoundError::R("not found");
        $this->import_props($row);
        $this->valid ? 0 : \Router\NotFoundError::R("not found");
        $this->images->load(static::MEDIA_CONTEXT, $this->id);
        $this->properties->load_from_database($this->id);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //int
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'price' => ['Float', 'Default0'], //string
            'days' => ['IntMore0', 'Default1'], //float
            'usages' => ['IntMore0', 'Default1'], //float
            'active' => ['Boolean', 'DefaultTrue'], //string
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string            
        ];
    }

    /**
     * 
     * @param int $id
     * @return \Content\ClientPackage\Package
     */
    public static function F(int $id = null): Package {
        return new static($id);
    }

    public static function reset_cache() {
        \Cache\FileBeaconDependency::F([static::CACHE_DEPENDENCY])->reset_dependency_beacons();
    }

    public static function get_class_version() {
        return md5(implode(".", [__FILE__, filemtime(__FILE__)]));
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function C(int $id) {
        $cache_key = implode(".", [static::CACHE_DEPENDENCY, $id]);
        $cache = \Cache\FileCache::F();
        $item = $cache->get($cache_key); /* @var $item static */
        $cs = static::class;
        
        if ($item && is_object($item) && ($item instanceof $cs) && $item->version === static::get_class_version()) {
            return $item;
        }
        $item = static::F($id);
        if ($item && $item->valid) {
            $cache->put($cache_key, $item, 0, \Cache\FileBeaconDependency::F([static::CACHE_DEPENDENCY]));
        } else {
            \Router\NotFoundError::R("not found");
        }
        return $item;
    }

}
