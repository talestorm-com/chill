<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_render_requested_product($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $requested_alias = \Router\Router::F()->route->get_params()->get_filtered("product_alias", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    if ($requested_alias) {
        //
        $product = \Content\Product\Product::C($requested_alias);
        if ($product && $product->product->enabled) {
            $tree = CatalogTree\CatalogTreeSinglet::F()->tree; /* @var $tree CatalogTree\CatalogTree */
            $fvc = 0;
            foreach ($product->product->catalogs as $catalog_entry) {/* @var $catalog_entry \DataModel\Product\Model\ProductCatalog */
                $node = $tree->get_item_by_id($catalog_entry->id); /* @var $node \CatalogTree\CatalogTreeItem */
                if ($node) {
                    if ($node->visible_parents) {
                        $fvc++;
                    }
                }
                if ($fvc) {
                    break;
                }
            }
            if ($fvc) {
                $product->render();
                \Content\last_products\LastProducts::register($product);
                return '';
            }
        }
    }
    \Router\NotFoundError::R("not found");
}
