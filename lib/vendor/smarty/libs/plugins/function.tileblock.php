<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_tileblock($params, $smtpl) {
    $assign = false;
    try {
        $params = is_array($params) ? $params : [];
        $pm = \DataMap\CommonDataMap::F()->rebind($params);
        $alias = $pm->get_filtered('alias', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $alias ? 0 : \Errors\common_error::R("no alias defined");
        $assign = $pm->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $tile = \Content\CatalogTile\CatalogTileFull::C($alias);
        if ($tile->tile->visible) { 
            if ($assign) {
                \smarty\SMW::F()->smarty->assign($assign, $tile->render(NULL, $tile->tile->template, true));
            } else {
                $tile->render(NULL, $tile->tile->template);
            }
        }
        return '';
    } catch (\Throwable $e) {
        $t = "<!-- {$e->getMessage()} in {$e->getFile()} at {$e->getLine()} -->";
        if ($assign) {
            smarty\SMW::F()->smarty->assign($assign, $t);
            return '';
        }
        return $t;
    }
}
