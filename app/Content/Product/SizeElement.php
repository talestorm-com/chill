<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Product;

/**
 * @property int $id
 * @property string $key
 * @property string $value
 * @property SizeElementEntry[] $alters
 * @property bool $valid
 */
class SizeElement implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc=""props>
    /** @var int */
    protected $id;

    /** @var string */
    protected $key;

    /** @var string */
    protected $value;

    /** @var SizeElementEntry[] */
    protected $alters;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__key() {
        return $this->key;
    }

    /** @return string */
    protected function __get__value() {
        return $this->value;
    }

    /** @return string[] */
    protected function __get__alters() {
        return $this->alters;
    }

    protected function __get__valid() {
        return ($this->id && $this->key && $this->value) ? true : false;
    }

    //</editor-fold>

    protected function __construct(array $row) {
        $this->alters = [];
        $this->id = \Filters\FilterManager::F()->apply_chain($row['id'], ['IntMore0', 'DefaultNull']);
        $this->value = \Filters\FilterManager::F()->apply_chain($row['shop_size'], ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $this->key = "P{$this->id}";
    }

    public function add_alter(array $row) {
        //C.alter_size,D.name,D.short_name,D.id sid
        $value = SizeElementEntry::F($row);
        $value && $value->valid ? $this->alters[$value->key] = $value : 0;
    }

    /**
     * 
     * @param int $id
     * @return SizeElementEntry
     */
    public function get_alter_by_id(int $id) {
        $key = "P{$id}";
        return $this->get_alter_by_key($key);
    }

    /**
     * 
     * @param string $key
     * @return SizeElementEntry
     */
    public function get_alter_by_key(string $key) {
        return array_key_exists($key, $this->alters) ? $this->alters[$key] : null;
    }
    
    public function get_alter_value_by_id(int $id,$default=null){
        return $this->get_alter_value_by_key("P{$id}",$default);
    }
    
    public function get_alter_value_by_key(string $key,$default=null){
        $value = $this->get_alter_by_key($key);
        return $value?$value->value:$default;
    }

    /**
     * 
     * @param array $row
     * @return \Content\Product\SizeElement
     */
    public static function F(array $row): SizeElement {
        return new static($row);
    }

}
