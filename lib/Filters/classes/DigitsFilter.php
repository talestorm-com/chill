<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class DigitsFilter extends \Filters\AbstractFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        return preg_replace('/\D/', '', $input_value);
    }

}
