<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_menu($params, $smtpl) {
    $params = is_array($params) ? $params : [];
    $pm = \DataMap\CommonDataMap::F()->rebind($params);
    $alias = $pm->get_filtered('alias', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $alias = $alias ? $alias : '-default-';
    $menu = \MenuTree\MenuTree::C($alias); /* @var $menu \MenuTree\MenuTree */
    $template = $pm->get_filtered('template', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $template ? 0 : $template = 'default';
    $item_template = $pm->get_filtered('item_template', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $item_template ? 0 : $item_template = 'default_item';
    
    return $menu->render(NULL, $template, $item_template, true);    
}
