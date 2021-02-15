<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\CatalogSizeDef;

/**
 * @property int $id
 * @property string $tmp_id
 * @property string $guid
 * @property string $size
 * @property WriterItemAlias[] $ext_sizes
 * @property bool $valid
 * @property bool $is_new
 */
class WriterItem {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport;

    /** @var int */
    protected $id;

    /** @var string */
    protected $tmp_id;

    /** @var string */
    protected $guid;

    /** @var string */
    protected $size;

    /** @var WriterItemAlias[] */
    protected $ext_sizes;

    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__tmp_id() {
        return $this->tmp_id;
    }

    /** @return string */
    protected function __get__guid() {
        return $this->guid;
    }

    /** @return string */
    protected function __get__size() {
        return $this->size;
    }

    /** @return WriterItemAlias[] */
    protected function __get__aliases() {
        return $this->ext_sizes;
    }

    /** @return WriterItemAlias[] */
    protected function __get__ext_sizes() {
        return $this->ext_sizes;
    }

    /** @return bool */
    protected function __get__valid() {
        return ($this->guid && $this->size && ($this->id || $this->__get__is_new())) ? true : false;
    }

    /** @return bool */
    protected function __get__is_new() {
        return $this->id ? false : true;
    }

    //</editor-fold>


    protected function __construct(array $data = null) {
        if ($data) {
            $this->import_props($data);
        }
    }

    protected function t_common_import_get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'tmp_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'guid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'size' => ['Strip', 'Trim', 'DefaultNull'],
            'aliases' => ['NEArray', 'DefaultEmptyArray'],
        ];
    }

    public function export_aliases(array &$e) {
        $e = array_merge($e, $this->ext_sizes);
        return $this;
    }

    protected function t_common_import_set_value_for_field_aliases($value) {
        $this->ext_sizes = [];
        if (is_array($value) && count($value)) {
            foreach ($value as $row) {
                $alias = WriterItemAlias::F($row)->set_parent_guid($this->guid);
                $alias && $alias->valid ? $this->ext_sizes[] = $alias : 0;
            }
        }
        return $this;
    }

    /**
     * 
     * @param array $data
     * @return \DataModel\CatalogSizeDef\WriterItems
     */
    public static function F(array $data = null): WriterItem {
        return new static($data);
    }

}
