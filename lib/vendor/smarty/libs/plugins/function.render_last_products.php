<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_render_last_products($aparams, $smtpl) {
    //if (DataMap\GPDataMap::F()->exists("debug_modules")) {
        $aparams = is_array($aparams) ? $aparams : [];
        $params = DataMap\CommonDataMap::F()->rebind($aparams);
        $last = Content\last_products\LastProducts::F();
        $template = $params->get_filtered("template", ["Strip", "Trim", "NEString", "DefaultNull"]);
        $template = $template ? $template : "default";
        $last->render(null, $template);
    //}
    return '';
}
