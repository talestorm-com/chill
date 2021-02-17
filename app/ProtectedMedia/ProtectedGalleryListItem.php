<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ProtectedMedia;

/**
 * Description of ProtectedGalleryListItem
 *
 * @author eve
 * 
 * @property string $uid
 * @property string $gallery_uid
 * @property int $owner_id
 * @property string $title
 * @property string $type
 * @property string $extension
 * @property int $sort
 * @property \DateTime $created
 * @property \DateTime $updated
 * @property string $class_version
 * @property string $content
 * @property string $preview 
 * @property bool $valid
 * @property double $aspect
 * 
 */
class ProtectedGalleryListItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $uid;

    /** @var string */
    protected $gallery_uid;

    /** @var int */
    protected $owner_id;

    /** @var string */
    protected $title;

    /** @var string */
    protected $type;

    /** @var string */
    protected $extension;

    /** @var int */
    protected $sort;

    /** @var \DateTime */
    protected $created;

    /** @var \DateTime */
    protected $updated;
    protected $class_version;
     /** @var double */
    protected $aspect;


    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__uid() {
        return $this->uid;
    }

    /** @return string */
    protected function __get__gallery_uid() {
        return $this->gallery_uid;
    }

    /** @return int */
    protected function __get__owner_id() {
        return $this->owner_id;
    }

    /** @return string */
    protected function __get__title() {
        return $this->title;
    }

    /** @return string */
    protected function __get__type() {
        return $this->type;
    }

    /** @return string */
    protected function __get__extension() {
        return $this->extension;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return \DateTime */
    protected function __get__created() {
        return $this->created;
    }

    /** @return \DateTime */
    protected function __get__updated() {
        return $this->updated;
    }

    /** @return string */
    protected function __get__class_version() {
        return $this->class_version;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->uid && $this->gallery_uid && $this->owner_id && $this->created;
    }

    /** @return string */
    protected function __get__content() {
        $request = \Router\Request::F();
        $extension = $this->extension && mb_strlen($this->extension, 'UTF-8') ? ".{$this->extension}" : "";
        return "{$request->request_protocol}://{$request->host}/media/private/{$this->gallery_uid}/{$this->uid}{$extension}";
    }

    /** @return string */
    protected function __get__preview() {
        $request = \Router\Request::F();
        return "{$request->request_protocol}://{$request->host}/media/private/{$this->gallery_uid}/preview/{$this->uid}.jpg";
    }
      /** @return double */
    protected function __get__aspect() {
        return $this->aspect;
    }

    //</editor-fold>


    public function __construct() {
        $this->class_version = static::file_ver();
    }

    /**
     * 
     * @return \ProtectedMedia\ProtectedGalleryListItem
     */
    public static function F(): ProtectedGalleryListItem {
        return new static();
    }

    public static function file_ver() {
        return md5(implode(".", [__FILE__, filemtime(__FILE__)]));
    }

    //<editor-fold defaultstate="collapsed" desc="marshaller_ops">
    protected function t_default_marshaller_export_property_created() {
        return $this->created ? $this->created->format('d.m.Y H:i:s') : null;
    }

    protected function t_default_marshaller_export_property_updated() {
        return $this->updated ? $this->updated->format("d.m.Y H:i:s") : null;
    }

    protected function t_default_marshaller_on_props_to_marshall(array &$props) {
        $props["content"] = "content";
        $props["preview"] = "preview";
    }

    protected function t_default_marshaller_export_property_content() {
        return $this->__get__content();
    }

    protected function t_default_marshaller_export_property_preview() {
        return $this->__get__preview();
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="importer ops">
    public function import_db_row(array $data): ProtectedGalleryListItem {
        $this->import_props($data, null, 'db');
        return $this;
    }

    protected function t_common_import_get_filters_params_for_db() {
        return [];
    }

    protected function t_common_import_get_filters_for_db() {
        return [
            'uid' => ['Trim', "NEString", "DefaultNull"], //string
            'gallery_uid' => ['Trim', "NEString", "DefaultNull"], //string            
            'owner_id' => ["IntMore0", "DefaultNull"], //int
            'title' => ['Trim', "NEString", "DefaultNull"], //string
            'type' => ['Trim', "NEString", "DefaultNull"], //string
            'extension' => ['Trim', "NEString", "DefaultNull"], //string
            'sort' => ["Int", "Default0"], //int
            'created' => ["DateMatch", "Defaultnull"], //\DateTime
            'updated' => ["DateMatch", "Defaultnull"], //\DateTime   
            'aspect' => ["Float", "Default0"],
        ];
    }

    //</editor-fold>    
}
