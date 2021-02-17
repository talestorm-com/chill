<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_display_ribbon_lent($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $lent = \Content\MediaContentRibbon\MediaContentRibbon::F(\Language\LanguageList::F()->get_current_language(), 0, 1000, false);
    $lent->render();
    return '';
}
