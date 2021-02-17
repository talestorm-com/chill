<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_render_requested_catalog($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $perpage = $params->get_filtered('perpage', ['IntMore0', 'DefaultNull']);
    $perpage ? 0 : $perpage = 24;
    $requested_alias = \Router\Router::F()->route->get_params()->get_filtered("catalog_alias", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $load_tile_count = \DataMap\GPDataMap::F()->get_filtered("load_tile_count", ['IntMore0', 'DefaultNull']);
    if (!$load_tile_count) {
        $load_tile_count = \DataMap\GPDataMap::F()->get_filtered("cp", ["IntMore0", "DefaultNull"]);
    }
    if ($requested_alias) {
        $tree = CatalogTree\CatalogTreeSinglet::F()->tree; /* @var $tree CatalogTree\CatalogTree */
        $node = $tree->get_item_by_alias($requested_alias);
        if ($node) {
            if (false && $node->has_visible_childs) { // redir if not end catalog?
                $child_node = $node->childs[0];
                \Router\Router::F()->redirect("/catalog/{$child_node->alias}");
            }
            $page = \Router\Router::F()->route->get_params()->get_filtered("catalog_page", ['IntMore0', 'Default0']);
            if ($page === 0 && $load_tile_count) {
                $catalog_content = \Content\Catalog\Catalog::C($node, 0, $load_tile_count, true,$perpage);
            } else {
                $catalog_content = \Content\Catalog\Catalog::C($node, $page, $perpage,false,$perpage);
            }
            $catalog_content->render();
            return '';
        }
    }
    \Router\NotFoundError::R("not found");
}
