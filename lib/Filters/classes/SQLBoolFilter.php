<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class SQLBoolFilter extends \Filters\AbstractFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if (is_bool($input_value)) {
            return $input_value ? 1 : 0;
        }
        return \Filters\InvalidValue::F("not a boolean");
    }

}
