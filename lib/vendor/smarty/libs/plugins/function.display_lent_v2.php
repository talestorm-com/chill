<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_display_lent_v2($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $requested_page = \Router\Router::F()->route->get_params()->get_filtered("page", ['IntMore0', 'Default0']);
    $lent = \Content\MediaContentRibbon\V2\RibbonLent::F(Language\LanguageList::F()->get_current_language(), $requested_page);    
    $requested_page?$lent->render(null, 'item_list') :$lent->render();
    return '';
}
