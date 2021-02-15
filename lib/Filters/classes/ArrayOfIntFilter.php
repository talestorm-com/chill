<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class ArrayOfIntFilter extends \Filters\AbstractFilter {

    use \Filters\TFilterCheckLength;

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if (is_array($input_value)) {
            $r = [];
            foreach ($input_value as $v) {
                $v = \Filters\FilterManager::F()->apply_filter($v, 'AnyInt', $params);
                \Filters\Value::is($v) ? false : $r[] = $v;
            }
            $check = $this->check_length_ok(count($r), $params);
            if ($check < 0) {
                return \Filters\InvalidValue::F("array too short");
            } else if ($check > 0) {
                return \Filters\InvalidValue::F("array too large");
            }
            return $r;
        } else {
            return \Filters\InvalidValue::F("not an array");
        }
    }

}
