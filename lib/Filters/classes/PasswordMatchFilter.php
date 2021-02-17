<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class PasswordMatchFilter extends \Filters\AbstractFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {

        return (is_string($input_value) && preg_match('/^[0-9a-z]{6,}$/i', $input_value)) ? $input_value : \Filters\InvalidValue::F("not match password pattern");
    }

}
