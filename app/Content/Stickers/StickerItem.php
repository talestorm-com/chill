<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Stickers;

/**
 * Description of StickerItem
 *
 * @author eve
 * @property int $id
 * @property string $name
 * @property string $cdn_id
 * @property string $cdn_url
 * @property bool $valid
 */
class StickerItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    const CACHE_DEP = "STICKERITEM";

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $cdn_id;

    /** @var string */
    protected $cdn_url;

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
    protected function __get__cdn_id() {
        return $this->cdn_id;
    }

    /** @return string */
    protected function __get__cdn_url() {
        return $this->cdn_url;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->name && $this->cdn_id && $this->cdn_url;
    }

    //</editor-fold>



    public function __construct() {
        ;
    }

    public function load_db(int $id) {
        $query = "SELECT * FROM chill__review__sticker WHERE id=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        is_array($row) ? 0 : $row = [];
        return $this->load_array($row);
    }

    public function load_array(array $data) {
        $this->import_props($data);
        return $this;
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //int
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id = null) {
        $r = new static();
        if ($id) {
            $r->load_db($id);
        }
        return $r;
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function FA(array $data) {
        $r = new static();
        $r->load_array($data);
        return $r;
    }

    public static function reset_cache() {
        \Cache\FileBeaconDependency::F([static::CACHE_DEP])->reset_dependency_beacons();
    }

}
