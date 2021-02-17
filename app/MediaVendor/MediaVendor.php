<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MediaVendor;

/**
 * Description of MediaVendor
 *
 * @author eve
 * @property int $id
 * @property string $common_name
 * @property string $image
 * @property string $name
 * @property string $intro
 * @property int $html_mode
 * @property string $info
 * @property boolean $valid
 * @property properties $properties
 */
class MediaVendor implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    const MEDIA_CONTEXT = "media_studio";
    const CACHE_DEPENDENCY = "MEDIA_STUDIO";

    private static $_file_version;

    public static function get_file_version() {
        if (!static::$_file_version) {
            static::$_file_version = md5(implode("-", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_file_version;
    }

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $common_name;

    /** @var string */
    protected $image;

    /** @var string */
    protected $name;

    /** @var string */
    protected $intro;

    /** @var int */
    protected $html_mode;

    /** @var string */
    protected $info;

    /** @var properties */
    protected $properties;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__common_name() {
        return $this->common_name;
    }

    /** @return string */
    protected function __get__image() {
        return $this->image;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__intro() {
        return $this->intro;
    }

    /** @return int */
    protected function __get__html_mode() {
        return $this->html_mode;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return boolean */
    protected function __get__valid() {
        return !!($this->id && $this->common_name);
    }

    /** @return properties */
    protected function __get__properties() {
        return $this->properties;
    }

    //</editor-fold>


    public function __construct() {
        $this->properties = properties::F();
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    /**
     * 
     * @param array $data
     * @return $this
     */
    public function load_from_array(array $data) {
        $this->import_props($data);
        return $this;
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //int
            'common_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'intro' => ['Trim', 'NEString', 'DefaultNull'], //string
            'html_mode' => ['IntMore0', 'Default0'], //int
            'info' => ['Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function load_from_db(int $id) {
        $lang = \Language\LanguageList::F()->get_current_language();
        $def = \Language\LanguageList::F()->get_default_language();

        $query = sprintf("SELECT A.id,A.common_name,A.image,COALESCE(B.name,C.name) name,
            COALESCE(B.html_mode,C.html_mode) html_mode,
            COALESCE(B.intro,C.intro) intro,
            COALESCE(B.info,C.info) info
            FROM media__studio A
            LEFT JOIN media__studio__strings__lang_%s B ON(A.id=B.id)
            LEFT JOIN media__studio__strings__lang_%s C ON(A.id=C.id)
            WHERE A.id=:P;
            ", $lang, $def);
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->load_from_array($row);
        $this->properties->load_from_database($this->id);
        return $this;
    }

    public static function reset_cached() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY)->reset_dependency_beacons();
    }

}
