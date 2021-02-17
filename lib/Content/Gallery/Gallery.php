<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Gallery;

/**
 * @property int $id 
 * @property string $alias 
 * @property string $guid
 * @property string $name 
 * @property string $info 
 * @property string $version
 * @property bool $html_mode
 * @property \Content\IImageCollection $images;
 */
class Gallery extends \Content\Content implements \Content\IImageSupport {

    use \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="const">
    const CACHE_DEP_BEACON = "gallery";
    const LOAD_MODE_DB = "database";
    const LOAD_MODE_POST = "post";

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $alias;

    /** @var string */
    protected $guid;

    /** @var string */
    protected $name;

    /** @var string */
    protected $info;

    /** @var string */
    protected $version;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var bool */
    protected $html_mode;

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
    protected function __get__guid() {
        return $this->guid;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    protected function __get__images() {
        return $this->images;
    }

    protected function __get__html_mode() {
        return $this->html_mode;
    }

    //</editor-fold>

    protected function __construct() {
        $this->version = static::get_file_ver();
    }

    protected function read_images() {
        $this->images = \Content\DefaultImageCollection::F("comon_gallery", (string) $this->id);
    }

    protected static function get_file_ver() {
        return md5(implode(":", [__CLASS__, filemtime(__FILE__)]));
    }

    protected static function cache_id(string $alias): string {
        return implode(":", [__CLASS__, $alias]);
    }

    public function load(string $alias, \DB\IDBAdapter $adapter = null) {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $row = $adapter->queryRow("SELECT * FROM gallery WHERE alias=:Palias;", [":Palias" => $alias]);
        if ($row) {
            $this->import_props($row, null, static::LOAD_MODE_DB);
            $this->read_images();
        } else {
            \Errors\common_error::R("not found");
        }
    }

    public function load_by_id(int $id, \DB\IDBAdapter $adapter = null) {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $row = $adapter->queryRow("SELECT * FROM gallery WHERE id=:Pid;", [":Pid" => $id]);
        if ($row) {
            $this->import_props($row, null, static::LOAD_MODE_DB);
            $this->read_images();
        } else {
            \Errors\common_error::R("not found");
        }
    }

    public function load_from_datamap(\DataMap\IDataMap $map) {
        $this->import_props_datamap($map, null, static::LOAD_MODE_POST);
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
        $this->alias = \Helpers\Helpers::uniqueAlias('gallery', $this->alias, $this->id, $adapter);
        $b = \DB\SQLTools\SQLBuilder::F($adapter);
        $tn = "@a" . md5(__METHOD__);
        if ($this->id) {
            $b->push("SET {$tn} = :P{$b->c}id;");
            $b->push_param(":P{$b->c}id", $this->id);
            $b->push("UPDATE gallery SET alias=:P{$b->c}alias,name=:P{$b->c}name,info=:P{$b->c}info,html_mode=:P{$b->c}html_mode WHERE id={$tn};");
        } else {
            $b->push("INSERT INTO gallery (alias,guid,name,info,html_mode) VALUES(:P{$b->c}alias,UUID(),:P{$b->c}name,:P{$b->c}info,:P{$b->c}html_mode);");
            $b->push("SET {$tn} = LAST_INSERT_ID();");
        }
        $b->push_params([
            ":P{$b->c}alias" => $this->alias,
            ":P{$b->c}name" => $this->name,
            ":P{$b->c}info" => $this->info,
            ":P{$b->c}html_mode" => $this->html_mode ? 1 : 0,
        ]);
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
            'guid' => ['Strip', 'Trim', 'NEString'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'html_mode' => ['Boolean', 'DefaultTrue'],
        ];
    }

    protected function t_common_import_get_filters_for_post() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'alias' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'html_mode' => ['Boolean', 'DefaultTrue'],
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
     * @return \Content\Gallery\Gallery
     */
    public static function F(): Gallery {
        return new static();
    }

    /**
     * 
     * @param int $id
     * @return \Content\Gallery\Gallery
     */
    public static function LI(int $id): Gallery {
        $r = new static();
        $r->load_by_id($id);
        return $r;
    }

    /**
     * 
     * @param string $alias
     * @return \Content\Gallery\Gallery
     */
    public static function LA(string $alias): Gallery {
        $r = new static();
        $r->load($alias);
        return $r;
    }

    /**
     * 
     * @param string $alias
     * @return \Content\Gallery\Gallery
     */
    public static function C(string $alias): Gallery {
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
