<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class GUIDFilter extends \Filters\AbstractFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if (is_string($input_value)) {
            if (preg_match('/^\s{0,}[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\s{0,}$/', $input_value)) {
                return trim($input_value);
            }
        }
        return \Filters\InvalidValue::F("not an GUID");
    }

}
