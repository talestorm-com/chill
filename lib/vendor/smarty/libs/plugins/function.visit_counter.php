<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_visit_counter($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = \DataMap\CommonDataMap::F()->rebind($aparams);
    $assign = $params->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $result = \VisitCounter\VisitCounter::F()->value;    
    if ($assign) {
        $smtpl->assign($assign, $result);
        return '';
    }
    return $result;
}
