<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_display_user_money($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $assign = $params->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $result = 0;
    if (Auth\Auth::F()->is_authentificated()) {
        $result = Filters\FilterManager::F()->apply_chain(DB\DB::F()->queryScalar("SELECT money FROM user__wallet WHERE id=:P", [":P" => Auth\Auth::F()->get_id()]), ['Float', 'Default0']);
    }
    if ($assign) {
        $smtpl->assign($assign, $result);
        return '';
    }
    return $result;
}
