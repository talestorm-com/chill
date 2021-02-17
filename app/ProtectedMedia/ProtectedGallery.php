<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ProtectedMedia;

/**
 * Description of ProtectedGallery
 *
 * @author eve
 * @property string $uid
 * @property int $owner_id
 * @property string $title
 * @property int $sort
 * @property string $info
 * @property \DateTime $created
 * @property \DateTime $updated
 * @property ProtectedGalleryListItem[] $items
 * @property string $class_version
 * @property bool $valid
 */
class ProtectedGallery implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $uid;

    /** @var int */
    protected $owner_id;

    /** @var string */
    protected $title;

    /** @var int */
    protected $sort;

    /** @var string */
    protected $info;

    /** @var \DateTime */
    protected $created;

    /** @var \DateTime */
    protected $updated;

    /** @var ProtectedGalleryListItem[] */
    protected $items;

    /** @var string */
    protected $class_version;

    

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__uid() {
        return $this->uid;
    }

    /** @return int */
    protected function __get__owner_id() {
        return $this->owner_id;
    }

    /** @return string */
    protected function __get__title() {
        return $this->title;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return \DateTime */
    protected function __get__created() {
        return $this->created;
    }

    /** @return \DateTime */
    protected function __get__updated() {
        return $this->updated;
    }

    /** @return ProtectedGalleryListItem[] */
    protected function __get__items() {
        return $this->items;
    }

    /** @return string */
    protected function __get__class_version() {
        return $this->class_version;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->uid && $this->owner_id && $this->created;
    }

   

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="factories">

    public function __construct() {
        $this->class_version = static::get_class_ver();
        $this->items = [];
    }

    public static function get_class_ver() {
        return md5(implode(",", [__FILE__, filemtime(__FILE__)]));
    }

    /**
     * 
     * @return \ProtectedMedia\ProtectedGallery
     */
    public static function F(): ProtectedGallery {
        return new static();
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="marshaller ops">
    protected function t_default_marshaller_export_property_created() {
        return $this->created ? $this->created->format("d.m.Y H:i:s") : null;
    }

    protected function t_default_marshaller_export_property_updated() {
        return $this->updated ? $this->updated->format('d.m.Y H:i:s') : null;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="import ops">

    public function import_from_db(array $data): ProtectedGallery {
        $this->import_props($data, null, "db");
        $items = array_key_exists('items', $data) && is_array($data["items"]) ? $data["items"] : [];
        //import items - need item class
        $this->import_items($items);
        return $this;
    }

    public function import_items(array $items): ProtectedGallery {
        $this->items = [];
        foreach ($items as $item) {
            $ti = ProtectedGalleryListItem::F()->import_db_row($item);
            $ti && $ti->valid ? $this->items[] = $ti : 0;
        }
        return $this;
    }

    protected function t_common_import_get_filters_params_for_db() {
        return [];
    }

    protected function t_common_import_get_filters_for_db() {
        return [
            'uid' => ["Trim", "NEString", "DefaultNull"], //string
            'owner_id' => ["IntMore0", "DefaultNull"], //int
            'title' => ["Trim", "NEString", "DefaultNull"], //string
            'sort' => ["Int", "Default0"], //int
            'info' => ["Trim", "NEString", "DefaultNull"], //string
            'created' => ["DateMatch", "DefaultNull"], //\DateTime
            'updated' => ["DateMatch", "DefaultNull"], //\DateTime
            
                //'items' => [], //ProtectedGalleryItem[] //items imports separately            
        ];
    }

    //</editor-fold>

    /**
     * 
     * @param string $uid
     * @param int $owner_id
     * @return \static|null
     */
    public static function LOAD(string $uid, int $owner_id) {
        $params = [
            ":Puid" => $uid,
            ":Powner_id" => $owner_id,
        ];
        $row = \DB\DB::F()->queryRow("SELECT A.*,B.created,B.updated FROM protected__gallery A LEFT JOIN protected__gallery__dates B 
            ON(A.uid=B.uid AND A.owner_id=B.owner_id)
            WHERE A.uid=:Puid AND A.owner_id=:Powner_id;", $params);
        if ($row) { 
            $row['items'] = \DB\DB::F()->queryAll("SELECT uid,gallery_uid,owner_id,title,type,extension,sort,created,updated,aspect
                FROM protected__gallery__item
                WHERE gallery_uid=:Puid AND owner_id=:Powner_id
                ", $params);
            try {
                return static::F()->import_from_db($row);
            } catch (\Throwable $e) {
                throw $e;
            }
        }
        return null;
    }

}
