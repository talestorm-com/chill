<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Model;

/**
 * @property int $id
 * @property int $sort
 * @property string $name
 * @property string $alias
 * @property bool $valid
 * @property string $path
 * @property string $guid
 */
class ProductCatalog implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $id;

    /** @var int */
    protected $sort;

    /** @var string */
    protected $name;

    /** @var string */
    protected $alias;

    /** @var string */
    protected $path;

    /** @var string */
    protected $guid;

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

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    /** @return bool */
    protected function __get__valid() {
        return ($this->id && $this->name && $this->alias) ? true : false;
    }

    protected function __get__path() {
        return $this->path;
    }

    protected function __get__guid() {
        return $this->guid;
    }

    //</editor-fold>

    public function __construct(array $x) {
        $this->import_props($x);
    }

    protected function t_common_import_get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //int
            'sort' => ['Int', 'Default0'], //int
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'alias' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string    
            'guid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    public static function F(array $x): ProductCatalog {
        return new static($x);
    }

    /**
     * 
     * @param \CatalogTree\CatalogTree $voc
     * @return $this
     */
    public function recover_path(\CatalogTree\CatalogTree $voc = null) {
        $voc = $voc ? $voc : \CatalogTree\CatalogTreeSinglet::F()->tree;
        $node = $voc->get_item_by_id($this->id);
        if ($node) {
            $this->path = $node->get_path(".");
        }
        return $this;
    }

}
