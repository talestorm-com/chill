<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_current_year($params, Smarty_Internal_Template $template) {
    return (new \DateTime())->format('Y');
}
