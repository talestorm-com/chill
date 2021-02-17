<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_lang_list($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $assign = $params->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $assign_current = $params->get_filtered('assign_current', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    if ($assign) {
        $smtpl->assign($assign, Language\LanguageList::F());
        if ($assign_current) {
            $smtpl->assign($assign_current, Language\LanguageList::F()->get_current_language());
        }
    }
    return '';
}
