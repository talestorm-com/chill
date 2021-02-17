<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class JSONStringFilter extends \Filters\AbstractFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        $value = $input_value;        
        $rv = mb_strlen($value, 'UTF-8') ? $value : \Filters\InvalidValue::F('empty string');        
        $ra = is_string($rv) ? json_decode($rv, TRUE) : $rv;        
        return $ra === null ? \Filters\InvalidValue::F("not json") : $ra;
    }

}
