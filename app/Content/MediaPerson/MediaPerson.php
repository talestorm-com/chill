<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaPerson;

/**
 * Description of MediaPerson
 *
 * @author eve
 * @property int $id
 * @property string $name
 * @property string $name_en
 * @property int $html_mode
 * @property string $image
 * @property string $intro
 * @property string $info
 * @property Properties $properties
 * @property string $class_version
 */
class MediaPerson extends \Content\Content {

    use \common_accessors\TCommonImport;

    const CACHE_BEACON = "MediaPerson";
    const MEDIA_CONTEXT = "media_person";

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;
    /** @var string */
    protected $name_en;

    /** @var int */
    protected $html_mode;
    

    /** @var string */
    protected $image;

    /** @var string */
    protected $intro;

    /** @var string */
    protected $info;

    /** @var Properties */
    protected $properties;
    protected $class_version;

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
    protected function __get__name_en() {
        return $this->name_en;
    }

    /** @return int */
    protected function __get__html_mode() {
        return $this->html_mode;
    }

    /** @return string */
    protected function __get__image() {
        return $this->image;
    }

    /** @return string */
    protected function __get__intro() {
        return $this->intro;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return Properties */
    protected function __get__properties() {
        return $this->properties;
    }

    protected function __get__class_version() {
        return $this->class_version;
    }

    //</editor-fold>


    protected function __construct(int $id) {
        $this->properties = Properties::F();
        $this->load($id);
        $this->class_version = static::get_class_version();
    }

    protected static function get_class_version() {
        return md5(implode(",", [__FILE__, filemtime(__FILE__)]));
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id) {
        return new static($id);
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function C(int $id) {
        $cache = \Cache\FileCache::F();
        $cache_key = __CLASS__ . "_{$id}";
        $x = $cache->get($cache_key);
        $cx = __CLASS__;
        if ($x && is_object($x) && ($x instanceof $cx) && $x->class_version === static::get_class_version()) {
            return $x;
        }
        $result = static::F($id);
        $cache_key = __CLASS__ . "_{$result->id}";
        $cache->put($cache_key, $result, 0, \Cache\FileBeaconDependency::F(static::CACHE_BEACON));
        return $result;
    }

    protected function load(int $id) {
        $query = sprintf("SELECT A.id,A.common_name name_en,COALESCE(B.name,C.name,'') name,A.image,COALESCE(B.html_mode,0) html_mode,
            COALESCE(B.intro,C.intro,'') intro,COALESCE(B.info,C.info,'') info
            FROM media__content__actor A
            LEFT JOIN media__content__actor__strings_lang_%s B ON(A.id=B.id)
            LEFT JOIN media__content__actor__strings_lang_%s C ON(A.id=C.id)
            WHERE A.id=:P", \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language());
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
    }

    protected function t_common_import_after_import() {
        parent::t_common_import_after_import();
        $this->properties->load_from_database($this->id);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'name_en' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'html_mode' => ['Int', 'Default0'], //boolean            
            'image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
        ];
    }

    public static function reset_cached() {
        \Cache\FileBeaconDependency::F(static::CACHE_BEACON)->reset_dependency_beacons();
    }

}
