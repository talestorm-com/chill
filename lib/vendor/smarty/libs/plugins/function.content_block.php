<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_content_block($params, $template) {
    $ap = is_array($params) ? $params : [];
    $pm = DataMap\CommonDataMap::F()->rebind($ap);
    $alias = $pm->get_filtered('alias', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $cb = $alias ? \Content\ContentBlock\ContentBlock::C($alias) : \Content\ContentBlock\ContentBlock::F();
    /* @var $cb \Content\ContentBlock\ContentBlock */
    $cb->render(NULL, 'NULL', false);
}
