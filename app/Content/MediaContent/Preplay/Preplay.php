<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Preplay;

/**
 * Description of Preplay
 *
 * @author eve
 * @property int $id
 * @property string $name
 * @property string $cdn_id
 * @property string $cdn_url
 * @property bool $valid
 * @property bool $applicable
 */
class Preplay implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="...">

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $cdn_id;

    /** @var string */
    protected $cdn_url;

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
        return $this->id && $this->name;
    }

    /** @return bool */
    protected function __get__applicable() {
        return $this->__get__valid() && $this->cdn_id && $this->cdn_url;
    }

    //</editor-fold>


    public function __construct(int $id) {
        if ($id) {
            $this->load($id);
        }
    }

    protected function load(int $id) {
        $row = \DB\DB::F()->queryRow("SELECT * FROM media__preplay__video WHERE id=:P", [":P" => $id]);
        $this->import_props(is_array($row) ? $row : []);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }
    
    
    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id){
        return new static($id);
    }

}
