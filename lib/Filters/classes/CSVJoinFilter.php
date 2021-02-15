<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class CSVJoinFilter extends \Filters\AbstractFilter {

    use \Filters\TFilterCheckLength;

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if (is_array($input_value)) {
            $separator = $params ? $params->get("separator", ",") : ",";
            $output_array = [];
            foreach ($input_value as $v) {
                $v = \Filters\FilterManager::F()->apply_filter($v, 'Trim');
                $v = \Filters\FilterManager::F()->apply_filter($v, 'NEString', $params);
                if (!\Filters\Value::is($v)) {
                    $output_array[] = $v;
                }
            }
            $check = $this->check_length_ok(count($output_array), $params);
            if ($check < 0) {
                return \Filters\InvalidValue::F("joined array too short");
            } else if ($check > 0) {
                return \Filters\InvalidValue::F("joined array too large");
            }
            return implode($separator, $output_array);
        } else {
            return \Filters\InvalidValue::F("not an array");
        }
    }

}
