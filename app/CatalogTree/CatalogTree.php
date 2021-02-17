<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CatalogTree;

class CatalogTree extends \Tree\Tree {

    CONST CACHE_BEAKON_DEPENDENCY = "front_catalog";

    /** @var CatalogTreeItem[] */
    protected $guid_index;

    /** @var CatalogTreeItem[] */
    protected $alias_index;

    public function load(): \Tree\ITree {
        $query = "SELECT id,parent_id,sort_order,name,alias,visible,guid,html_mode,default_image,import_processor,terminal FROM catalog__group";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $this->cache_key]);
        $this->import($rows);
        return $this;
    }

    public static function transform_arg_to_string($argument = null): string {
        return "catalog_short";
    }

    public function get_cache_dependency() {
        return \Cache\FileBeaconDependency::F('front_menus,front_catalog');
    }

    public function get_node_instance(): \Tree\ITreeNode {
        return CatalogTreeItem::F();
    }

    public static function clear_dependency_beacon() {
        \Cache\FileBeaconDependency::F('front_menus,front_catalog')->reset_dependency_beacons();
    }

    public function __wakeup() {
        parent::__wakeup();
        $this->guid_index = null;
        $this->alias_index = null;
    }

    protected function rebuid_guid_index() {
        if (!$this->guid_index) {
            $c = [];
            foreach ($this->root as $item) {/* @var $item CatalogTreeItem */
                $item->rebuild_guid($c);
            }
            $this->guid_index = $c;
        }
    }

    protected function rebuid_alias_index() {
        if (!$this->alias_index) {
            $c = [];
            foreach ($this->root as $item) {/* @var $item CatalogTreeItem */
                $item->rebuild_alias($c);
            }
            $this->alias_index = $c;
        }
    }

    /**
     * 
     * @param string $guid
     * @param type $default
     * @return CatalogTreeItem
     */
    public function get_item_by_guid(string $guid, $default = null) {
        $this->rebuid_guid_index();
        return array_key_exists($guid, $this->guid_index) ? $this->guid_index[$guid] : $default;
    }

    /**
     * 
     * @param string $alias
     * @param type $default
     * @return CatalogTreeItem
     */
    public function get_item_by_alias(string $alias, $default = null) {
        $this->rebuid_alias_index();
        return array_key_exists($alias, $this->alias_index) ? $this->alias_index[$alias] : $default;
    }

}
