<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class ArrayFilter extends \Filters\AbstractFilter {

    use \Filters\TFilterCheckLength;

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if (is_array($input_value)) {
            $check = $this->check_length_ok(count($input_value), $params);
            if ($check < 0) {
                return \Filters\InvalidValue::F("array too small");
            } else if ($check > 0) {
                return \Filters\InvalidValue::F("array too large");
            }
            return $input_value;
        }
        return \Filters\InvalidValue::F("not an array");
    }

}
