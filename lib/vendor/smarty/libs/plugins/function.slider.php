<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_slider($params, $smtpl) {
    $params = is_array($params) ? $params : [];
    $pm = \DataMap\CommonDataMap::F()->rebind($params);
    $alias = $pm->get_filtered('alias', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $alias = $alias ? $alias : '-default-';
    $assign = $pm->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $error_message = null;
    try {
        $ds = DIRECTORY_SEPARATOR;
        $slider = Content\Slider\Slider::C($alias);
        $slider_layout=$slider->layout;
        if(DataMap\GPDataMap::F()->exists("slider_layout")){
            $slider_layout = DataMap\GPDataMap::F()->get_filtered("slider_layout",["Strip","Trim","NEString","DefaultNull"]);
        }
        $slider_layout = $slider_layout?$slider_layout:$slider->layout;
        $template = "layouts{$ds}layout_{$slider_layout}";
        $rv = $slider->render(NULL, $template, true);
        if ($assign) {
            \smarty\SMW::F()->smarty->assign($assign, $rv);
            return null;
        }
        return $rv;
    } catch (\Throwable $e) {
        $error_message = sprintf("error `%s` in `%s` at `%s`\n\nstacktrace:\n%s", $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
    }
    $t = "<!-- {$error_message} -->";
    if ($assign) {
        \smarty\SMW::F()->smarty->assign($assign, $t);
    }
    return $t;
}
