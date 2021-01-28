<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

interface IFilterParams {

    /**
     * checks when named param exists
     * @param string $param_name
     * @return bool
     */
    public function exists(string $param_name): bool;

    /**
     * returns value of named param
     * @param string $param_name
     * @param mixed $default
     * @return mixed
     */
    public function get(string $param_name, $default = null);

    /**
     * mnemonic name of filter
     * @return string 
     */
    public function get_filter_name(): string;

    /**
     * set property (only builder must set props)
     * @param string $param_name
     * @param mixed $param_value
     * @return IFilterParams
     */
    public function set(string $param_name, $param_value): IFilterParams;
}
