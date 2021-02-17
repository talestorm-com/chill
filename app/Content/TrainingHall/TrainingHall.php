<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\TrainingHall;

/**
 * Description of TrainingHall
 *
 * @author eve
 * @property int $id
 * @property string $name
 * @property string $address
 * @property float $lat
 * @property float $lon
 * @property TrainingHallFeature[] $features
 * @property string $default_image
 * @property bool $valid
 * @property string $version
 * @property string $phone
 * @property \Content\IImageCollection $images
 * @property Properties $properties
 */
class TrainingHall extends \Content\Content implements \Content\IImageSupport {

    use \common_accessors\TCommonImport;

    CONST MEDIA_CONTEXT = 'training_hall';
    CONST CACHE_DEPENDENCY = 'training_hall';

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $address;

    /** @var float */
    protected $lat;

    /** @var float */
    protected $lon;

    /** @var TrainingHallFeature[] */
    protected $features = [];

    /** @var string */
    protected $default_image;

    /** @var string */
    protected $version;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var Properties */
    protected $properties;

    /** @var string */
    protected $phone;

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

    /** @return string */
    protected function __get__address() {
        return $this->address;
    }

    /** @return float */
    protected function __get__lat() {
        return $this->lat;
    }

    /** @return float */
    protected function __get__lon() {
        return $this->lon;
    }

    /** @return TrainingHallFeature[] */
    protected function __get__features() {
        return $this->features;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->name && $this->address;
    }

    /** @return string */
    protected function __get__default_image() {
        return $this->default_image;
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

    /** @return string */
    protected function __get__phone() {
        return $this->phone;
    }
    
    
   

    //</editor-fold>



    public function load(int $id) {        
        $query = "SELECT * FROM fitness__places WHERE id=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
      //  var_dump($row);die();
        $row && is_array($row) ? 0 : \Router\NotFoundError::R("not found");
        $this->import_props($row);          
        $this->valid ? 0 : \Router\NotFoundError::R("not found");
        $this->images->load(static::MEDIA_CONTEXT, $this->id);
        $this->properties->load_from_database($this->id);
    }

    protected function t_common_import_after_import() {
        $this->features = TrainigHallFeature::from_json_array($this->features);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //int
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'address' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'lat' => ['Float', 'DefaultNull'], //float
            'lon' => ['Float', 'DefaultNull'], //float
            'features' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'phone' => ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull'],
        ];
    }

    public function __construct(int $id = null) {
        $this->version = static::get_class_version();
        $this->images = \Content\DefaultImageCollection::F();
        $this->properties = Properies::F();
        if ($id) {
            $this->load($id);
        }
    }

    /**
     * 
     * @param int $id
     * @return \Content\TrainingHall\TrainingHall
     */
    public static function F(int $id = null): TrainingHall {
        return new static($id);
    }

    public static function reset_cache() {
        \Cache\FileBeaconDependency::F([static::CACHE_DEPENDENCY])->reset_dependency_beacons();
    }

    public static function get_class_version() {
        return md5(implode(".", [__FILE__, filemtime(__FILE__)]));
    }

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

    public function get_has_images(): bool {
        return $this->images->get_has_images();
    }

    public function get_images_count(): int {
        return $this->images->get_images_count();
    }

    public function get_object_images(): \Content\IImageCollection {
        return $this->images;
    }

}
