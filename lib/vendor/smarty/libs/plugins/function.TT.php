<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_TT($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $assign = $params->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $lang = $params->get_filtered('l', ['Strip','Trim','NEString','DefaultNull']);
    $lang = $lang?$lang: Language\LanguageList::F()->get_current_language()->id;
    $term = $params->get_filtered('t', ['Strip','Trim','NEString','DefaultNull']);
    return $term?\Language\LanguageTokenList::F($lang)->t($term):$term;    
}
