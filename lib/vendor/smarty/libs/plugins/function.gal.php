<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_gal($params, $template) {
    $ap = is_array($params) ? $params : [];
    $pm = DataMap\CommonDataMap::F()->rebind($ap);
    $template = $pm->get_filtered("tpl", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $template = $template ? $template : "default";
    $assign = $pm->get_filtered("assign", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $smarty = \smarty\SMW::F()->smarty;
    $gallery = $smarty->getTemplateVars('gallery_holder');    
    if (!($gallery && is_object($gallery) && ($gallery instanceof \Content\IImageSupport))) {
        $gallery = $smarty->getTemplateVars('this');
    }
    if (($gallery && is_object($gallery) && ($gallery instanceof \Content\IImageSupport))) {
        if ($assign) {
            $smarty->assign($assign, \Content\GalleryRenderer\GalleryRenderer::F($gallery)->render($smarty, $template, true));
        } else {
            \Content\GalleryRenderer\GalleryRenderer::F($gallery)->render($smarty, $template, false);
        }
    }
    return '';
}
