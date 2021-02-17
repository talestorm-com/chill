<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_display_genred_lent($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $genre_id = \Router\Router::F()->route->get_params()->get_filtered("genre_id", ['IntMore0', 'Default0']);
    $lent = \Content\MediaContentRibbon\MediaContentGenredList::F($genre_id, 0, 100, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language());
    $lent->render();
    return '';
}
