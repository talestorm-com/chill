<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

/**
 * interface for filters params set for one property
 */
interface IFilterParamSet {

    /**
     * 
     * @param string $filter_name
     * @return \Filters\IFilterParams
     */
    public function get(string $filter_name);

    public function set(\Filters\IFilterParams $value): IFilterParamSet;

    /**
     * clear specified filter params or all filter params if $filter_name is null
     * @param string $filter_name
     */
    public function reset(string $filter_name = null): IFilterParamSet;

    public function exists(string $filter_name): bool;

    public function get_property_name(): string;
}
