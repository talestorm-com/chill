<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class PhoneClearFilter extends DigitsFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        $result = parent::do_apply($input_value, $params);
        $m = [];
        if (preg_match('/^(?P<cc>\d{0,})(?P<tc>\d{3})(?P<oc1>\d{3})(?P<oc2>\d{2})(?P<oc3>\d{2})/', $result, $m)) {
            return $result;
        }
        return \Filters\InvalidValue::F("not phone number");
    }

}
