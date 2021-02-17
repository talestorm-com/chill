<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile\Loader;

class LoaderChildCatalogs extends AbstractLoader {

    protected static function get_loader_description(): string {
        return "Дочерние каталоги 1 уровня каждого выбранного каталога";
    }

    protected static function get_loader_name(): string {
        return "ChildCatalogs";
    }
    public function load(\Content\CatalogTile\CatalogTile $tile, \Content\CatalogTile\CatalogTileFull $full_tile = null):array {    
        $result = [];
        foreach ($tile->catalogs as $tile_catalog) { /* @var $tile_catalog \Content\CatalogTile\CatalogEntry */
            $catalog_node = \CatalogTree\CatalogTreeSinglet::F()->tree->get_item_by_id($tile_catalog->id);
            if ($catalog_node) { /* @var $catalog_node \CatalogTree\CatalogTreeItem */
                if ($tile->ignore_catalog_visibility || $catalog_node->visible) {
                    $local_nodes = [];
                    foreach ($catalog_node->childs as $child_node) {/* @var $child_node \CatalogTree\CatalogTreeItem */
                        if ($tile->ignore_catalog_visibility || $child_node->visible) {
                            $node = \Content\CatalogTile\CatalogEntry::F();
                            $node->fill_with_tree_item($child_node);
                            $local_nodes[] = $node;
                        }
                    }
                    if (count($local_nodes)) {
                        usort($local_nodes, function(\Content\CatalogTile\CatalogEntry $a, \Content\CatalogTile\CatalogEntry $b) {
                            /* @var $a \Content\CatalogTile\CatalogEntry */
                            /* @var $b \Content\CatalogTile\CatalogEntry */
                            $r = $a->sort - $b->sort;
                            return $r === 0 ? ($a->id - $b->id) : $r;
                        });
                        $result = array_merge($result, $local_nodes);
                    }
                }
            }
        }
        return $result;
    }

}
