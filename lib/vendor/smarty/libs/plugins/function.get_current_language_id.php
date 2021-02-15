<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_get_current_language_id() {

    return \Language\LanguageList::F()->get_current_language()->id;
}
