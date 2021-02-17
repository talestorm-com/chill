<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_common_map($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $template = $params->get_filtered('wrapper_template', ['Strip', "Trim", "NEString", "DefaultNull"]);
    $template ? 0 : $template = "wrapper_default";
    $map = Content\CommonMap\CommonMap::F($params);
    $map->render(null, $template);
    return '';
}
