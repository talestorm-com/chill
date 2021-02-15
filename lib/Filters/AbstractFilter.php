<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

abstract class AbstractFilter implements IFilter {

    protected function is_default_filter(): bool {
        return false;
    }

    protected function __construct() {
        ;
    }

    public final function apply($input_value, IFilterParams $params = null) {
        if ($this->is_default_filter()) {
            return Value::is($input_value) ? $this->do_apply($input_value, $params) : $input_value;
        } else {
            return Value::is($input_value) ? $input_value : $this->do_apply($input_value, $params);
        }
    }

    protected abstract function do_apply($input_value, IFilterParams $params = null);

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

}
