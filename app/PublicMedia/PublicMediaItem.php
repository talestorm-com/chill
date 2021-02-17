<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia;

/**
 * Description of PublicMediaItem
 *
 * @author eve
 * @property int $id
 * @property int $gallery_id
 * @property int $owner_id 
 * @property string $title
 * @property bool $active
 * @property bool $gallery_visible displays when parent gallery is visible
 * @property string $type
 * @property string $safe_type returns type of octet-stream if empty
 * @property string $extension
 * @property string $dotted_extension  extension perfixed by dot or empty string if extension is empty
 * @property double $aspect	
 * @property double $preview_aspect	
 * @property int $sort
 * @property \DateTime $created 
 * @property string $version
 * @property string $info
 * @property PublicTag[] $tags
 * @property bool $valid
 * 
 * 
 * Ключ по галерее - иначе сброс постоянный
 */
class PublicMediaItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    protected static $_fv = null;
    //<editor-fold defaultstate="collapsed" desc="props">

    
    protected $id;
   
    /** @var int */
    protected $gallery_id;

    /** @var int */
    protected $owner_id;

    /** @var string */
    protected $title;

    /** @var bool */
    protected $active;

    /** @var string */
    protected $type;

    /** @var string */
    protected $extension;

    /** @var double */
    protected $aspect;

    /** @var double */
    protected $preview_aspect;

    /** @var int */
    protected $sort;

    /** @var \DateTime */
    protected $created;

    /** @var string */
    protected $version;    
    protected $gallery_visible;

    /** @var string */
    protected $info;

    /** @var PublicTag[] */
    protected $tags;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__gallery_id() {
        return $this->gallery_id;
    }

    /** @return int */
    protected function __get__owner_id() {
        return $this->owner_id;
    }

    /** @return string */
    protected function __get__title() {
        return $this->title;
    }

    /** @return bool */
    protected function __get__active() {
        return $this->active;
    }

    /** @return string */
    protected function __get__type() {
        return $this->type;
    }

    protected function __get__safe_type() {
        return \Helpers\Helpers::NEString($this->type, "application/octet-stream");
    }

    protected function __get__dotted_extension() {
        $x = \Helpers\Helpers::NEString($this->extension, null);
        return $x ? ".{$x}" : "";
    }

    /** @return string */
    protected function __get__extension() {
        return $this->extension;
    }

    /** @return double */
    protected function __get__aspect() {
        return $this->aspect;
    }

    /** @return double */
    protected function __get__preview_aspect() {
        return $this->preview_aspect;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return \DateTime */
    protected function __get__created() {
        return $this->created;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id  && $this->owner_id && $this->created && $this->type && $this->gallery_id;
    }

    protected function __get__gallery_visible() {
        return $this->gallery_visible;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return PublicTag[] */
    protected function __get__tags() {
        return $this->tags;
    }

    //</editor-fold>


    public static function get_file_ver() {
        if (!static::$_fv) {
            static::$_fv = md5(implode("--", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_fv;
    }

    public function __construct() {
        $this->version = static::get_file_ver();
    }

    public static function F(): PublicMediaItem {
        return new static();
    }

    protected static function query_proto(): string {
        return "SELECT A.id,B.owner_id,A.title,A.active,A.type,A.extension,A.aspect,A.preview_aspect,A.sort,A.created,
            A.gallery_id,B.visible gallery_visible,A.info
            FROM public__gallery__item A 
            JOIN public__gallery B ON(A.gallery_id=B.id)
            WHERE %s";
    }

    /**
     * 
     * @param int $id     
     * @return \PublicMedia\PublicMediaItem
     */
    public function load(int $id): PublicMediaItem {
        $qs = sprintf(static::query_proto(), "A.id=:P ");
        $data = \DB\DB::F()->queryRow($qs, [":P" => $id]);
        if ($data) {
            $this->load_array($data);
        }
        return $this;
    }

    public function load_by_uid_only(int $id): PublicMediaItem {
       return $this->load($id);
    }

    /**
     * 
     * @param int $id     
     * @return \PublicMedia\PublicMediaItem
     */
    public function load_gid(int $id): PublicMediaItem {
        return $this->load($id);
    }

    /**
     * 
     * @param array $data
     * @return \PublicMedia\PublicMediaItem
     */
    public function load_array(array $data): PublicMediaItem {
        $this->import_props($data);
        return $this;
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //string            
            'owner_id' => ['IntMore0', 'DefaultNull'], //int
            'title' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'active' => ['Boolean', 'DefaultFalse'], //bool
            'type' => ['Trim', 'NEString', 'DefaultNull'], //string
            'extension' => ['Trim', 'NEString', 'DefaultNull'], //string
            'aspect' => ['Float', 'DefaultNull'], //double
            'preview_aspect' => ['Float', 'DefaultNull'], //double
            'sort' => ['AnyInt', 'Default0'], //int
            'created' => ['DateMatch', 'DefaultNull'], //\DateTime
            'gallery_id' => ["IntMore0", "DefaultNull"],
            'gallery_visible' => ["Boolean", "DefaultTrue"],
            'info' => ["Trim", "NEString", "DefaultEmptyString"],
        ];
    }

    protected function t_common_import_after_import() {
        $this->tags = [];
        if ($this->gallery_id && $this->id) {
            $this->tags = PublicTag::get_tags_of_gallery_item_v2($this->id);
        }
    }

    protected function t_default_marshaller_export_property_created() {
        return $this->created ? $this->created->format('d.m.Y H:i:s') : null;
    }
    

    /**
     * 
     * @param int $gallery_id
     * @return PublicMediaItem[]
     */
    public static function load_by_gallery_id(int $gallery_id): array {
        $query = sprintf(static::query_proto(), "B.id=:PP ORDER BY sort,created DESC,A.id");
        $rows = \DB\DB::F()->queryAll($query, [":PP" => $gallery_id]);
        return static::create_items_array($rows);
    }

    /**
     * 
     * @param array $data
     * @return PublicMediaItem|null
     */
    public static function create_item_array(array $data) {
        $x = new static();
        $x->load_array($data);
        return $x && $x->valid ? $x : null;
    }

    /**
     * 
     * @param array $data
     * @return PublicMediaItems[]
     */
    public static function create_items_array(array $data): array {
        $result = [];
        foreach ($data as $row) {
            $x = static::create_item_array($row);
            $x ? $result[] = $x : 0;
        }
        return $result;
    }

    public function get_preview_path() {
        return PublicMediaGallery::gallery_files_path($this->gallery_id) . "preview.{$this->id}.jpg";
    }

    public function get_media_path() {
        return PublicMediaGallery::gallery_files_path($this->gallery_id) . "{$this->id}{$this->__get__dotted_extension()}";
    }

}
