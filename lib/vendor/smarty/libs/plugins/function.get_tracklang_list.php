<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_get_tracklang_list($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $assign = $params->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    if ($assign) {
        $language = \Language\LanguageList::F()->get_current_language();
        $def_language = \Language\LanguageList::F()->get_default_language();
        $query = "
            SELECT A.id,COALESCE(S1.name,S2.name) name
            FROM media__content__tracklang A
            LEFT JOIN media__content__tracklang__strings S1 ON(S1.id=A.id AND S1.language_id='%s')
            LEFT JOIN media__content__tracklang__strings S2 ON(S2.id=A.id AND S2.language_id='%s')
            ORDER BY A.sort,A.id DESC            
            ";
        $rows = \DB\DB::F()->queryAll(sprintf($query, $language, $def_language));
        $smtpl->assign($assign, $rows);
    }
    return '';
}
