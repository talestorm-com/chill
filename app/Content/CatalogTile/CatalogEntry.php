<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile;

/**
 * @property int $id
 * @property string $alias
 * @property string $path
 * @property string $name
 * @property string $override
 * @property int $sort
 * @property string $image_id
 * @property string $default_image
 * @property bool $valid
 * @property bool $visible
 * @property string $display_name
 * @property bool $has_image
 * @property \CatalogTree\CatalogTreeItem $source_catalog
 */
class CatalogEntry implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $id;

    /** @var string */
    protected $alias;

    /** @var string */
    protected $path;

    /** @var string */
    protected $name;

    /** @var string */
    protected $override;

    /** @var int */
    protected $sort;

    /** @var string */
    protected $image_id;

    /** @var string */
    protected $default_image;

    /** @var bool */
    protected $visible;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    /** @return string */
    protected function __get__path() {
        return $this->path;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__override() {
        return $this->override;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return string */
    protected function __get__image_id() {
        return $this->image_id;
    }

    /** @return string */
    protected function __get__default_image() {
        return $this->default_image;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->alias && $this->name;
    }

    /** @return bool */
    protected function __get__visible() {
        return $this->visible;
    }

    protected function __get__display_name() {
        return $this->override ? $this->override : $this->name;
    }

    protected function __get__has_image() {
        return ($this->default_image || $this->image_id) ? true : false;
    }

    protected function __get__source_catalog() {
        return \CatalogTree\CatalogTreeSinglet::F()->tree->get_item_by_id($this->id);
    }

    //</editor-fold>

    /**
     * 
     * @param array $data
     */
    public function __construct(array $data = null) {
        if ($data) {
            $this->import_props($data);
        }
    }

    public function fill_with_tree_item(\CatalogTree\CatalogTreeItem $item) {
        $this->alias = $item->alias;
        $this->default_image = $item->default_image;
        $this->path = $item->get_path("\\");
        $this->name = $item->name;
        $this->override = null;
        $this->sort = $item->sort_order;
        $this->image_id = null;
        $this->visible = $item->visible;
    }

    /**
     * 
     * @param array $data
     * @return \Content\CatalogTile\CatalogEntry
     */
    public static function F(array $data = null): CatalogEntry {
        return new static($data);
    }

    protected function t_common_import_get_filters() {
        return [
            "id" => ["IntMore0", "DefaultNull"], //int
            "alias" => ["Strip", 'Trim', 'NEString', 'DefaultNull'], //string            
            "name" => ["Strip", 'Trim', 'NEString', 'DefaultNull'], //string
            "override" => ["Strip", 'Trim', 'NEString', 'DefaultNull'], //string
            "sort" => ["Int", 'Default0'], //int
            "image_id" => ["Strip", 'Trim', 'NEString', 'DefaultNull'], //string
            "default_image" => ["Strip", 'Trim', 'NEString', 'DefaultNull'], //string            
            "visible" => ["Boolean", "DefaultTrue"], //bool            
        ];
    }

    /**
     * 
     * @param \CatalogTree\CatalogTree $x
     * @return $this
     */
    public function merge_node_data(\CatalogTree\CatalogTree $x) {
        $node = $x->get_item_by_id($this->id);
        if ($node) {
            $this->path = $node->get_path("\\");
        }
        return $this;
    }

}
