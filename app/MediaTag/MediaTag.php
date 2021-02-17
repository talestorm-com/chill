<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MediaTag;

/**
 * Description of Genre
 *
 * @author eve
 * @property int $id
 * @property int $sort
 * @property string[] $name
 * @property boolean $valid
 * 
 */
class MediaTag implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var int */
    protected $sort;

    /** @var string[] */
    protected $name;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return string[] */
    protected function __get__name() {
        return $this->name;
    }

    /** @return boolean */
    protected function __get__valid() {
        return $this->id && is_array($this->name) &&  count($this->name);
    }

    //</editor-fold>


    public function __construct(int $id = null) {
        $this->name = [];
        if ($id) {
            $this->load($id);
        }
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id = null) {
        return new static($id);
    }

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function load(int $id) {
        $row = \DB\DB::F()->queryRow("SELECT * FROM media__content__tag WHERE id=:P", [":P" => $id]);
        $this->import_props(is_array($row) ? $row : []);
        return $this;
    }

    protected function t_common_import_get_filters(): array {
        return[
            'id' => ['IntMore0', 'DefaultNull'],
            'sort' => ['Int', 'Default0'],
        ];
    }

    protected function t_common_import_after_import() {
        if ($this->id) {
            $rows = \DB\DB::F()->queryAll("SELECT * FROM media__content__tag__strings WHERE id=:P", [":P" => $this->id]);
            
            $ni = [];
            foreach ($rows as $row) {
                $language_id = \Filters\FilterManager::F()->apply_chain($row["language_id"], ['Strip', 'Trim', 'NEString', 'DefaultNull']);
                $name = \Filters\FilterManager::F()->apply_chain($row["name"], ['Strip', 'Trim', 'NEString', 'DefaultNull']);
                if ($language_id && $name) {
                    $ni[$language_id] = $name;
                }
            }
            $this->name = $ni;            
        }        
    }

}
