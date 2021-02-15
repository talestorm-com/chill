<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video;

/**
 * @author eve
 * @property int $id   parent preset id
 * @property string $uid   unique preset indentifier
 * @property string $name  this preset name
 * @property string $image  this preset image
 * @property string $info   this preset about
 * @property string $video
 * @property bool $html_mode about mode
 * @property bool $valid   internal checker
 * @property int $sort
 * @property int $marshall_mode
 * @property string $package_name
 * @property string $class_version
 * @property float $package_cost
 * @property string $mime
 */
class VideoItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    const MEDIA_CONTEXT = "video_item";

    protected static $cv = null;
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $uid;

    /** @var string */
    protected $name;

    /** @var string */
    protected $image;

    /** @var string */
    protected $info;

    /** @var bool */
    protected $html_mode;

    /** @var int */
    protected $sort;

    /** @var int */
    protected $marshall_mode;

    /** @var string */
    protected $package_name;

    /** @var string */
    protected $class_version;

    /** @var float */
    protected $package_cost;

    /** @var string */
    protected $video;

    /** @var string */
    protected $mime;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__uid() {
        return $this->uid;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__image() {
        return $this->image;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return bool */
    protected function __get__html_mode() {
        return $this->html_mode;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->uid && $this->id; //loaded!
    }

    protected function __set__marshall_mode(int $x) {
        $this->marshall_mode = $x;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return int */
    protected function __get__marshall_mode() {
        return $this->marshall_mode;
    }

    /** @return string */
    protected function __get__package_name() {
        return $this->package_name;
    }

    /** @return string */
    protected function __get__class_version() {
        return $this->class_version;
    }

    /** @return float */
    protected function __get__package_cost() {
        return $this->package_cost;
    }

    /** @return string */
    protected function __get__video() {
        return $this->video;
    }

    /** @return string */
    protected function __get__mime() {
        return $this->mime;
    }

    //</editor-fold>






    public function __wakeup() {
        $this->marshall_mode = VideoGroup::MARSHALL_MODE_FULL;
    }

    public function __construct(array $data = null) {
        $this->class_version = static::get_class_ver();
        if ($data) {
            $this->import($data);
        }
    }

    /**
     * 
     * @param array $data
     * @return \Content\Video\VideoItem
     */
    public function import(array $data): VideoItem {
        $this->import_props($data);
        return $this;
    }

    /**
     * 
     * @param array $data
     * @return \Content\Video\VideoItem
     */
    public static function F(array $data = null): VideoItem {
        return new static($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'uid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'info' => ['Trim', 'NEString', 'DefaultNull'],
            'video' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'html_mode' => ['Boolean', 'DefaultTrue'],
            'sort' => ["Int", "Default0"],
            'package_name' => ["Strip", "Trim", "NEString", "DefaultNull"],
            'package_cost' => ["Float", "Default0"],
            'mime' => ['Trim', 'NEString', 'DefaultNull'],
        ];
    }

    public static function get_class_ver(): string {
        if (!static::$cv) {
            static::$cv = md5(implode(",", [__FILE__, filemtime(__FILE__),]));
        }
        return static::$cv;
    }

    protected function t_default_marshaller_on_props_to_marshall(array &$props) {
        if ($this->marshall_mode === VideoGroup::MARSHALL_MODE_TRIM) {
            if (array_key_exists("html_mode", $props)) {
                unset($props["html_mode"]);
            }
            if (array_key_exists("marshall_mode", $props)) {
                unset($props["marshall_mode"]);
            }
        }
    }

    /**
     * 
     * @param int $id
     * @param string $uid
     * @return \Content\Video\VideoItem
     */
    public static function C(int $id, string $uid): VideoItem {
        $cache_key = implode(".", [__CLASS__, $id, $uid]);
        $item = \Cache\FileCache::F()->get($cache_key); /* @var $item static */
        $cc = static::class;
        if ($item && is_object($item) && ($item instanceof $cc) && $item->class_version === static::get_class_ver()) {
            return $item;
        }
        $item = static::F();
        $item->load_id($id, $uid);
        \Cache\FileCache::F()->put($cache_key, $item, 0, \Cache\FileBeaconDependency::F(VideoGroup::CACHE_BEAKON));
        return $item;
    }

    /**
     * 
     * @param string $uid
     * @return \Content\Video\VideoItem
     */
    public static function C2(string $uid): VideoItem {
        $cache_key = implode(".", [__CLASS__, "Z", $uid]);
        $item = \Cache\FileCache::F()->get($cache_key); /* @var $item static */
        $cc = static::class;
        if ($item && is_object($item) && ($item instanceof $cc) && $item->class_version === static::get_class_ver()) {
            return $item;
        }
        $item = static::F();
        $item->load_uid($uid);
        \Cache\FileCache::F()->put($cache_key, $item, 0, \Cache\FileBeaconDependency::F(VideoGroup::CACHE_BEAKON));
        return $item;
    }

    /**
     * 
     * @param int $id
     * @param string $uid
     * @return \Content\Video\VideoItem
     */
    public function load_id(int $id, string $uid): VideoItem {
        $query = "SELECT A.*,B.name package_name,B.cost package_cost FROM video__group__item A JOIN video__group B ON(A.id=B.id) WHERE A.id=:P AND A.uid=:PP AND B.active=1";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id, ":PP" => $uid]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import($row);
        return $this;
    }

    /**
     * 
     * @param int $id
     * @param string $uid
     * @return \Content\Video\VideoItem
     */
    public function load_id_ignore_activity(int $id, string $uid): VideoItem {
        $query = "SELECT A.*,B.name package_name,B.cost package_cost FROM video__group__item A JOIN video__group B ON(A.id=B.id) WHERE A.id=:P AND A.uid=:PP";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id, ":PP" => $uid]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import($row);
        return $this;
    }

    /**
     * 
     * @param string $uid
     * @return \Content\Video\VideoItem
     */
    protected function load_uid(string $uid): VideoItem {
        $query = "SELECT A.*,B.name package_name,B.cost package_cost FROM video__group__item A JOIN video__group B ON(A.id=B.id) WHERE  A.uid=:PP AND B.active=1";
        $row = \DB\DB::F()->queryRow($query, [":PP" => $uid]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import($row);
        return $this;
    }

}
