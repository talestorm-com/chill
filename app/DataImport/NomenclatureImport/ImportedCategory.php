<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\NomenclatureImport;

/**
 * @property string $uid  ic uid
 * @property string $name  name
 * @property string $parent parent uid
 * @property string $parent_key   A{parent_uid}
 * @property string $info     category description
 * @property ImportedCategory[] $childs
 * @property bool $valid 
 * @property string $category_key A{uid}
 * @property string $alias  
 * @property int $id
 * @property int $parent_id
 */
class ImportedCategory implements \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TIterator;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $uid;

    /** @var string */
    protected $name;

    /** @var string */
    protected $parent;

    /** @var string */
    protected $parent_key;

    /** @var string */
    protected $info;

    /** @var ImportedCategory[] */
    protected $childs;

    /** @var string */
    protected $category_key;

    /** @var string */
    protected $alias;

    /** @var int */
    protected $id;
    protected $parent_id;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__uid() {
        return $this->uid;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__parent() {
        return $this->parent;
    }

    /** @return string */
    protected function __get__parent_key() {
        return $this->parent_key;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return ImportedCategory[] */
    protected function __get__childs() {
        return $this->childs;
    }

    protected function __get__valid() {
        return $this->uid && $this->name;
    }

    /** @return string */
    protected function __get__category_key() {
        return $this->category_key;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    protected function __get__id() {
        return $this->id;
    }

    protected function __get__parent_id() {
        return $this->parent_id;
    }

    //</editor-fold>

    protected function t_iterator_get_internal_iterable_name() {
        return 'childs';
    }

    public function add_child(ImportedCategory $child) {
        $this->childs[] = $child;
    }

    protected function __construct() {
        
    }

    /**
     * 
     * @param \DataMap\IDataMap $map
     * @return \DataImport\NomenclatureImport\ImportedCategory
     */
    public static function F(\DataMap\IDataMap $map): ImportedCategory {
        $r = new static();
        $r->import_props_datamap($map);
        return $r;
    }

    /**
     * 
     * @param array $map
     * @return \DataImport\NomenclatureImport\ImportedCategory
     */
    public static function FA(array $map): ImportedCategory {
        $r = new static();
        $r->import_props($map);
        return $r;
    }

    protected function t_common_import_get_filters() {
        return [
            'uid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'parent' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'info' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

    protected function t_common_import_after_import() {
        if (is_numeric($this->parent) && intval($this->parent) === 0) {
            $this->parent = null;
        }
        $this->parent_key = $this->parent === null ? null : "A{$this->parent}";
        $this->childs = [];
        $this->category_key = "A{$this->uid}";
        return $this;
    }

    public function set_exists_id(int $id) {
        $this->id = $id;
    }

    public function set_exists_parent_id(int $id) {
        $this->parent_id = $id;
    }

}
