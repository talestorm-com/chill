<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_get_user_auth_status($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $assign = $params->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $result = false;
    if (Auth\Auth::F()->is_authentificated()) {
        $result = true;
    }
    if ($assign) {
        $smtpl->assign($assign, $result);
        return '';
    }
    return $result;
}
