<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\CategoryImport;

/**
 * @property string $uid
 * @property string $name
 * @property string $parent
 * @property string $parent_key
 * @property string $info
 * @property ImportedCategory[] $childs
 * @property bool $valid
 * @property string $category_key
 * @property string $alias
 */
class ImportedCategory implements \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TIterator;

    protected $uid;
    protected $name;
    protected $parent;
    protected $parent_key;
    protected $childs;
    protected $info;
    protected $category_key;
    protected $_alias;

    protected function __construct(\DataMap\IDataMap $datamap) {
        $this->import_props_datamap($datamap);
    }

    public function import_props(array $data, \common_accessors\IFilterValueResolver $resolver = null) {
        \Errors\common_error::R("not supported");
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

    /**
     * 
     * @param \DataMap\IDataMap $map
     * @return \DataImport\CategoryImport\ImportedCategory
     */
    public static function F(\DataMap\IDataMap $map): ImportedCategory {
        return new static($map);
    }

    protected function __get__uid() {
        return $this->uid;
    }

    protected function __get__name() {
        return $this->name;
    }

    protected function __get__parent() {
        return $this->parent;
    }

    protected function __get__parent_key() {
        return $this->parent_key;
    }

    protected function __get__info() {
        return $this->info;
    }

    protected function __get__valid() {
        return $this->uid && $this->name;
    }

    protected function __get__childs() {
        return $this->childs;
    }

    protected function __get__category_key() {
        return $this->category_key;
    }

    protected function __get__alias() {
        if (!$this->_alias) {
            $this->_alias = \Helpers\Helpers::translit($this->name);
        }
        return $this->_alias;
    }

    protected function t_iterator_get_internal_iterable_name() {
        return 'childs';
    }

    public function add_child(ImportedCategory $child) {
        $this->childs[] = $child;
    }

}
