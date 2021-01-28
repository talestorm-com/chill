<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_get_media_reviews($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $assign = $params->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $qty = $params->get_filtered('q', ['IntMore0', 'DefaultNull']);
    $qty ? 0 : $qty = 5;
    $id = $params->get_filtered('id', ['IntMore0', 'DefaultNull']);
    if ($assign) {
        if ($id) {
            $rows = \Review\ContentReviewsList::F($id, $qty);
            $smtpl->assign($assign, $rows);
        } else {
            $smtpl->assign($assign, []);
        }
    }
    return '';
}
