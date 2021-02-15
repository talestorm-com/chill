<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\PropertyCollection;

/**
 * @property string $name
 * @property string $property_name
 * @property string $value
 * @property string $property_value
 * @property int $sort
 * @property bool $valid
 * @property string $key
 */
class DefaultPropertyItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">    
    /** @var string */
    protected $property_name;

    /** @var string */
    protected $property_value;

    /** @var int */
    protected $sort;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__name() {
        return $this->property_name;
    }

    /** @return string */
    protected function __get__property_name() {
        return $this->property_name;
    }

    /** @return string */
    protected function __get__value() {
        return $this->property_value;
    }

    /** @return string */
    protected function __get__property_value() {
        return $this->property_value;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return bool */
    protected function __get__valid() {
        return ($this->property_name && $this->property_value) ? true : false;
    }

    protected function __get__key() {
        return $this->property_name;
    }

    //</editor-fold>



    protected function __construct(array $input = null) {
        if ($input) {
            $this->import_props($input);
        }
    }

    protected function t_common_import_get_filters() {
        return [
            'property_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'property_value' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'sort' => ['Int', 'Default0'],
        ];
    }

    public function set_up(string $property_name = null, string $property_value = null, int $sort = 0) {
        $this->import_props(compact('property_name', 'property_value', 'sort'));
    }

    /**
     * 
     * @param array $input
     * @return \Content\PropertyCollection\DefaultPropertyItem
     */
    public static function F(array $input = null): DefaultPropertyItem {
        return new static($input);
    }

    public function set_value($value) {
        $this->value = \Filters\FilterManager::F()->apply_chain($value, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    }

}
