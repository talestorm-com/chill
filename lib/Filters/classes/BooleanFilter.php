<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class BooleanFilter extends \Filters\AbstractFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if ($input_value === true || $input_value === '1' || $input_value === 1 || (strcasecmp($input_value, 'true') === 0) || (strcasecmp($input_value, 'on') === 0)) {
            return true;
        }
        if ($input_value === false || $input_value === 0 || $input_value === '0' || (strcasecmp($input_value, 'false') === 0) || (strcasecmp($input_value, 'off') === 0)) {
            return false;
        }
        return \Filters\InvalidValue::F("not a bool");
    }

}
