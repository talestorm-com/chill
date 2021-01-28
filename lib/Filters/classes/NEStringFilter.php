<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class NEStringFilter extends \Filters\AbstractFilter {

    use \Filters\TFilterCheckInt;

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if (mb_strlen($input_value, 'UTF-8')) {
            $value = $input_value;
            $len = mb_strlen($value, 'UTF-8');
            $check_result = $this->check_int_ok($len, $params);
            if ($check_result < 0) {
                return \Filters\InvalidValue::F("string too short");
            } else if ($check_result > 0) {
                return \Filters\InvalidValue::F("string too large");
            }
            return $value;
        }
        return \Filters\InvalidValue::F("empty string");
    }

}
