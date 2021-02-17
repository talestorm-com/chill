<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\CatalogSizeDef;

/**
 * @property int $id
 * @property string $size
 * @property string $parent_guid
 * @property bool $valid
 */
class WriterItemAlias {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport;

    protected $i;
    protected $v;
    protected $parent_guid;

    protected function __get__id() {
        return $this->i;
    }

    protected function __get__size() {
        return $this->v;
    }

    protected function __get__parent_guid() {
        return $this->parent_guid;
    }

    protected function __get__valid() {
        return $this->i && $this->v && $this->parent_guid && CatalogSizeDefVoc::F()->exists($this->i);
    }

    protected function __construct(array $data = null) {
        if ($data) {
            $this->import_props($data);
        }
    }

    /**
     * 
     * @param string $x
     * @return $this
     */
    public function set_parent_guid($x) {
        $this->parent_guid = \Helpers\Helpers::NEString($x, null);
        return $this;
    }

    protected function t_common_import_get_filters() {
        return [
            'i' => ['IntMore0', 'DefaultNull'],
            'v' => ['Strip', 'Trim', 'NEString', 'DefaultNull']
        ];
    }

    /**
     * 
     * @param array $data
     * @return \DataModel\CatalogSizeDef\WriterItemAlias
     */
    public static function F(array $data = null): WriterItemAlias {
        return new static($data);
    }

}
