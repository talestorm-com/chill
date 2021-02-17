<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

/**
 * pool contains params for multiple filters of multiple properties
 * pool can be instantiated from json, xml tag or php array
 */
interface IParamPool {

    /** for single-property pools use this default property name */
    const DEFAULT_PROP_ID = 'f74754d6c97d40adaa1b681e2371c680';

    public function get_params_for(string $filter_name, string $prop_name = IParamPool::DEFAULT_PROP_ID): IFilterParams;

    public function exists(string $filter_name, string $prop_name = IParamPool::DEFAULT_PROP_ID): bool;

    public function has_params_for_property(string $prop_name = IParamPool::DEFAULT_PROP_ID): bool;

    public function set_params_for_prop_filter(IFilterParams $params, string $prop_name = IParamPool::DEFAULT_PROP_ID): IParamPool;

    public function set_params_for_property(IFilterParamSet $param);

    public function get_param_set_for_property(string $prop_name = IParamPool::DEFAULT_PROP_ID): IFilterParamSet;
}
