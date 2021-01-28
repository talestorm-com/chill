<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class AnyIntFilter extends \Filters\AbstractFilter {

    use \Filters\TFilterCheckInt;

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if (!is_numeric($input_value)) {
            return \Filters\InvalidValue::F("not integer");
        }
        $value = intval($input_value);
        
        $check_result = $this->check_int_ok($value, $params);
        if ($check_result < 0) {
            return \Filters\InvalidValue::F("value too small");
        } else if ($check_result > 0) {
            return \Filters\InvalidValue::F("value too large");
        }        
        return $value;
    }

}
