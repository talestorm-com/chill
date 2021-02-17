<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\TagList;

/**
 * Description of TagListItem
 *
 * @author eve
 * @property int $id
 * @property string $name
 * @property string $text
 * @property int $sort
 */
class TagListItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var int */
    protected $sort;

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

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    protected function __get__text() {
        return $this->name;
    }

    //</editor-fold>

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'sort' => ['Int', 'Default0'], //int
        ];
    }

    /**
     * 
     * @param array $data
     * @return $this
     */
    public function load(array $data) {
        $this->import_props($data);
        return $this;
    }

    public function __construct(array $data = null) {
        if ($data) {
            $this->load($data);
        }
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data = null) {
        return new static($data);
    }

    protected function t_default_marshaller_on_props_to_marshall(array &$props) {
        $props["text"] = "text";
    }
    
    protected function t_default_marshaller_export_property_text() {
        return $this->name;
    }

}
