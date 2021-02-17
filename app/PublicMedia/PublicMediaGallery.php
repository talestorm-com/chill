<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia;

/**
 * Description of PublicMediaGallery
 * ридер публичной галереи
 * @author eve
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property string $info
 * @property boolean $visible
 * @property \DateTime $updated
 * @property string[] $tags
 * @property string $version
 * @property int $qty
 * @property PublicMediaItemShort[] $items
 * @property bool $valid 
 * @property float $cover_aspect
 * 
 */
class PublicMediaGallery implements \common_accessors\IMarshall, \Iterator, \Countable {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport,
        \common_accessors\TIterator;

    const CACHE_BEAKON = "public_media_gallery_%s"; // ключ кеша не должен быть общим!!!!
    const COVER_NAME = "cover";

    protected static $_fv = null;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var int */
    protected $owner_id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $info;

    /** @var boolean */
    protected $visible;

    /** @var \DateTime */
    protected $updated;

    /** @var string[] */
    protected $tags;

    /** @var string */
    protected $version;

    /** @var int */
    protected $qty;
    protected $cover_aspect;

    /** @var PublicMediaItemShort[] */
    protected $items;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__owner_id() {
        return $this->owner_id;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return boolean */
    protected function __get__visible() {
        return $this->visible;
    }

    /** @return \DateTime */
    protected function __get__updated() {
        return $this->updated;
    }

    /** @return string[] */
    protected function __get__tags() {
        return $this->tags;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    /** @return int */
    protected function __get__qty() {
        return $this->qty;
    }

    /** @return PublicMediaItemShort[] */
    protected function __get__items() {
        return $this->items;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->owner_id ? true : false;
    }

    protected function __get__cover_aspect() {
        return $this->cover_aspect;
    }

    //</editor-fold>


    protected function __construct() {
        $this->version = static::get_file_ver();
        $this->tags = [];
        $this->items = [];
    }

    protected static function load_query() {
        return "SELECT A.*,C.qty,T.info,U.updated
            FROM public__gallery A LEFT JOIN public__gallery__counter C ON(C.id=A.id)
            LEFT JOIN public__gallery__text T ON(T.id=A.id)
            LEFT JOIN public__gallery__up U ON(U.id=A.id) ";
    }

    public function load_by_id(int $id): PublicMediaGallery {
        $query = sprintf('%s %s', static::load_query(), " WHERE A.id=:P");
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        if ($row) {
            $this->import_props($row);
        }
        return $this;
    }
    

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //int
            'owner_id' => ['IntMore0', 'DefaultNull'], //int
            'name' => ['Trim', 'NEString', 'DefaultNull'], //string
            'info' => ['Trim', 'NEString', 'DefaultNull'], //string
            'visible' => ['Boolean', 'DefaultTrue'], //boolean
            'updated' => ['DateMatch', 'DefaultNull'], //\DateTime                        
            'qty' => ['IntMore0', 'Default0'], //int
            'cover_aspect' => ['Float', 'Default0']
        ];
    }

    protected function t_common_import_after_import() {
        $this->load_tags();
        $this->load_images();
    }

    /**
     * 
     * @return $this
     */
    protected function load_tags() {
        $this->tags = [];
        if ($this->id) {
            $this->tags = PublicTag::get_tags_of_gallery($this->id);
        }
        return $this;
    }

    /**
     * 
     * @return $this
     */
    protected function load_images() {
        $this->items = [];
        if ($this->id) {
            $this->items = PublicMediaItemShort::load_by_gallery_id($this->id);
        }
        return $this;
    }

    /**
     * 
     * @return \PublicMedia\PublicMediaGallery
     */
    public static function F(): PublicMediaGallery {
        return new static();
    }

    /**
     * 
     * @param int $id gallery_id
     * @return \PublicMedia\PublicMediaGallery
     */
    public static function C(int $id): PublicMediaGallery {
        $cache_key = implode("----:----", [__CLASS__, $id]);
        $item = \Cache\FileCache::F()->get($cache_key); /* @var $item static */
        $cs = static::class;
        if ($item && is_object($item) && ($item instanceof $cs) && $item->version === static::get_file_ver()) {
            return $item;
        }
        $nitem = static::F()->load_by_id($id);
        if ($nitem && $nitem->valid) {
            \Cache\FileCache::F()->put($cache_key, $nitem, 0, \Cache\FileBeaconDependency::F([sprintf(static::CACHE_BEAKON, $nitem->id)]));
            return $nitem;
        }
        \Errors\common_error::R("not found");
    }    

    /**
     * 
     * @return string
     */
    public static function get_file_ver() {
        if (static::$_fv === null) {
            static::$_fv = md5(implode(".", [__FILE__, filemtime(__FILE__),]));
        }
        return static::$_fv;
    }

    protected function t_default_marshaller_export_property_updated() {
        return $this->updated ? $this->updated->format('d.m.Y H:i:s') : null;
    }

    public function destroy_cache() {
        $keys = [
            implode("----:----", [__CLASS__, $this->id]),
        ];
        $cache = \Cache\FileCache::F();
        foreach ($keys as $key) {
            $cache->remove($key);
        }
    }

    /**
     * 
     * @return string
     */
    public function get_files_path(): string {
        return static::gallery_files_path((string)$this->id);
    }

    /**
     * 
     * @param int $id
     * @return string
     */
    public static function gallery_files_path(int $id): string {
        return \Config\Config::F()->PUBLIC_STORAGE_BASE . (string)$id . DIRECTORY_SEPARATOR;
    }

    public static function reset_cache_for(int $id = null) {
        $caches = [];
        if ($id) {
            $caches[] = sprintf(static::CACHE_BEAKON, $id);
        }        
        if (count($caches)) {
            foreach ($caches as $key) {
                \Cache\FileBeaconDependency::F($key)->reset_dependency_beacons();
            }
        }
    }

}
