<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_display_page_content($params, $template) {
    return \Out\Out::F()->get('page_content');
}
