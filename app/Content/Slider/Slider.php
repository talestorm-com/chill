<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Slider;

/**
 * @property int $id 
 * @property string $alias 
 * @property string $title 
 * @property string $layout
 * @property string $version
 * @property int $timeout
 * @property \Content\IImageCollection $images;
 * @property bool $crop_fill
 * @property bool $crop
 * @property SliderPropertyCollection $properties
 * @property string $background
 */
class Slider extends \Content\Content implements \Content\IImageSupport {

    use \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="const">
    const MEDIA_CONTEXT = "common_slider";
    const CACHE_DEP_BEACON = "slider";
    const LOAD_MODE_DB = "database";
    const LOAD_MODE_POST = "post";

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $alias;

    /** @var string */
    protected $title;

    /** @var string */
    protected $layout;

    /** @var string */
    protected $version;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var int */
    protected $timeout;
    protected $crop_fill;
    protected $crop;
    protected $properties;
    protected $background;

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
    protected function __get__layout() {
        return $this->layout;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    protected function __get__images() {
        return $this->images;
    }

    protected function __get__timeout() {
        return $this->timeout;
    }

    protected function __get__crop_fill() {
        return $this->crop_fill;
    }

    protected function __get__crop() {
        return $this->crop;
    }

    protected function __get__properties() {
        return $this->properties;
    }

    protected function __get__background() {
        return $this->background;
    }

    //</editor-fold>

    protected function __construct() {
        $this->version = static::get_file_ver();
        $this->properties = SliderPropertyCollection::F();
    }

    protected function read_images() {
        $this->images = \Content\DefaultImageCollection::F(static::MEDIA_CONTEXT, (string) $this->id);
    }

    protected static function get_file_ver() {
        return md5(implode(":", [__CLASS__, filemtime(__FILE__)]));
    }

    protected static function cache_id(string $alias): string {
        return implode(":", [__CLASS__, $alias]);
    }

    public function load(string $alias, \DB\IDBAdapter $adapter = null) {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $row = $adapter->queryRow("SELECT * FROM slider WHERE alias=:Palias;", [":Palias" => $alias]);
        if ($row) {
            $this->import_props($row, null, static::LOAD_MODE_DB);
            $this->read_images();
            $this->properties->load_from_database($this->id, $adapter);
        } else {
            \Errors\common_error::R("not found");
        }
    }

    public function load_by_id(int $id, \DB\IDBAdapter $adapter = null) {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $row = $adapter->queryRow("SELECT * FROM slider WHERE id=:Pid;", [":Pid" => $id]);
        if ($row) {
            $this->import_props($row, null, static::LOAD_MODE_DB);
            $this->read_images();
            $this->properties->load_from_database($this->id, $adapter);
        } else {
            \Errors\common_error::R("not found");
        }
    }

    public function load_from_datamap(\DataMap\IDataMap $map) {
        $this->import_props_datamap($map, null, static::LOAD_MODE_POST);
        $this->properties->load_from_object_array($map->get_filtered('properties', ['NEArray', 'DefaultEmptyArray']));
    }

    /**
     * 
     * @param \DB\IDBAdapter $adapter
     * @return int  id of saved block
     */
    public function save(\DB\IDBAdapter $adapter = null): int {
        if (!$this->alias) {
            $this->alias = \Helpers\Helpers::translit($this->name);
        }
        $this->alias = \Helpers\Helpers::uniqueAlias('slider', $this->alias, $this->id, $adapter);
        $b = \DB\SQLTools\SQLBuilder::F($adapter);
        $tn = "@a" . md5(__METHOD__);
        if ($this->id) {
            $b->push("SET {$tn} = :P{$b->c}id;");
            $b->push_param(":P{$b->c}id", $this->id);
            $b->push("UPDATE slider SET alias=:P{$b->c}alias,title=:P{$b->c}title,
                layout=:P{$b->c}layout,timeout=:P{$b->c}timeout,crop_fill=:P{$b->c}crop_fill,
                crop=:P{$b->c}crop,background=:P{$b->c}background WHERE id={$tn};");
        } else {
            $b->push("INSERT INTO slider (alias,title,layout,timeout,crop_fill,crop,background) 
                VALUES(:P{$b->c}alias,:P{$b->c}title,:P{$b->c}layout,:P{$b->c}timeout,:P{$b->c}crop_fill,:P{$b->c}crop,:P{$b->c}background);");
            $b->push("SET {$tn} = LAST_INSERT_ID();");
        }
        $b->push_params([
            ":P{$b->c}alias" => $this->alias,
            ":P{$b->c}title" => $this->title,
            ":P{$b->c}layout" => $this->layout,
            ":P{$b->c}timeout" => $this->timeout,
            ":P{$b->c}crop_fill" => $this->crop_fill,
            ":P{$b->c}crop" => $this->crop,
            ":P{$b->c}background" => $this->background,
        ]);
        $this->properties->save($b, $tn);
        $ret = $b->execute_transact($tn);
        static::RESET_CACHE();
        return $ret;
    }

    public static function RESET_CACHE() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEP_BEACON)->reset_dependency_beacons();
    }

    protected function t_common_import_get_filters_for_database() {
        return [
            'id' => ['IntMore0'],
            'alias' => ['Strip', 'Trim', 'NEString'],
            'title' => ['Strip', 'Trim', 'NEString'],
            'layout' => ['Strip', 'Trim', 'NEString'],
            'timeout' => ['IntMOre0'],
            'crop_fill' => ['Boolean', 'DefaultFalse'],
            'crop' => ['Boolean', 'DefaultTrue'],
            'background' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    protected function t_common_import_get_filters_for_post() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'alias' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'title' => ['Strip', 'Trim', 'NEString'],
            'layout' => ['Strip', 'Trim', 'NEString'],
            'timeout' => ['IntMore0',],
            'crop_fill' => ['Boolean', 'DefaultFalse', 'SQLBool'],
            'crop' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'background' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    protected function t_common_import_get_filters_params_for_post() {
        return [];
    }

    protected function t_common_import_get_filters_params_for_database() {
        return [];
    }

    /**
     * 
     * @return \Content\Slider\Slider
     */
    public static function F(): Slider {
        return new static();
    }

    /**
     * 
     * @param int $id
     * @return \Content\Slider\Slider
     */
    public static function LI(int $id): Slider {
        $r = new static();
        $r->load_by_id($id);
        return $r;
    }

    /**
     * 
     * @param string $alias
     * @return \Content\Slider\Slider
     */
    public static function LA(string $alias): Slider {
        $r = new static();
        $r->load($alias);
        return $r;
    }

    /**
     * 
     * @param string $alias
     * @return \Content\Slider\Slider
     */
    public static function C(string $alias): Slider {
        $cache = \Cache\FileCache::F();
        $result = $cache->get(static::cache_id($alias)); /* @var $result static */
        $cm = static::class;
        if ($result && is_object($result) && ($result instanceof $cm) && $result->version === static::get_file_ver()) {
            return $result;
        } else {
            $result = static::LA($alias);
            $cache->put(static::cache_id($result->alias), $result, 0, \Cache\FileBeaconDependency::F(static::CACHE_DEP_BEACON));
        }
        return $result;
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
