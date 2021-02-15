<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class DefaultHTMLColorFilter extends \Filters\AbstractDefaultFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if ($params && $params->exists("default")) {
            if (preg_match("/^#[0-9a-f]{6}/i", $params->get("default"))) {
                return $params->get("default");
            }
        }
        return "#000000";
    }

}
