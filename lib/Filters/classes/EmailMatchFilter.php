<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class EmailMatchFilter extends \Filters\AbstractFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        $m = trim(strip_tags($input_value));
        return preg_match('/^[^@\,\;\s]{1,}@[^@\,\;\s]{1,}\.[^@\.\,\;\s]{1,}$/', $m) ? $m : \Filters\InvalidValue::F("not an email");
    }

}
