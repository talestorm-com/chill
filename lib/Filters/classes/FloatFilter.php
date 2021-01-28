<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

Class FloatFilter extends \Filters\AbstractFilter {

    use \Filters\TFilterCheckFloat;
    

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if (is_string($input_value)) {
            $input_value = str_ireplace(",", ".", $input_value);
            $input_value = preg_replace("/[^+\-0-9\.]/i", "", $input_value);
        }
        if (is_numeric($input_value)) {
            $value = floatval($input_value);
            $check = $this->check_float_ok($value, $params);
            if ($check < 0) {
                return \Filters\InvalidValue::F("value too small");
            } else if ($check > 0) {
                return \Filters\InvalidValue::F("value too large");
            }
            return $value;
        }
        return \Filters\InvalidValue::F("NaN");
    }

}
