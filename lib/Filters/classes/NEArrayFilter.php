<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class NEArrayFilter extends \Filters\AbstractFilter {

    use \Filters\TFilterCheckInt;

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        if (is_array($input_value)){
            return count($input_value)?$input_value:\Filters\InvalidValue::F("array is empty");
        }        
        return \Filters\InvalidValue::F("not an array");
    }

}
