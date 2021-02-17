<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_get_last_contents($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $assign = $params->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $qty = $params->get_filtered('q', ['IntMore0', 'DefaultNull']);
    $qty ? 0 : $qty = 5;
    $ctype = $params->get_filtered('ct', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $ctype ? 0 : $ctype = md5(implode("", [microtime(true)]));
    if ($assign) {
        $language = \Language\LanguageList::F()->get_current_language();
        $def_language = \Language\LanguageList::F()->get_default_language();
        $list = \Content\MediaContentRibbon\MediaContentLastXListTyped::F($ctype, $qty, $language, $def_language);
        $smtpl->assign($assign, $list);
    }
    return '';
}
