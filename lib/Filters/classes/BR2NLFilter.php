<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class BR2NLFilter extends \Filters\AbstractFilter {
   
    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if (is_string($input_value)) {
            return str_ireplace(['<br>', '<br/>'], "\n", $input_value);
        }
        return \Filters\InvalidValue::F("not a string");
    }

}
