<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\params;

/**
 * keeps params for all filters of one property
 * 
 * @property string $property_name
 */
class FilterParamSet implements \Filters\IFilterParamSet {

    use \common_accessors\TCommonAccess;

    /** @var string */
    protected $property_name = \Filters\IParamPool::DEFAULT_PROP_ID;

    /** @var \Filters\IFilterParams[] */
    protected $filter_params; // named by filter

    protected function __get__property_name() {
        return $this->property_name;
    }

    public function __construct(string $prop_name = \Filters\IParamPool::DEFAULT_PROP_ID) {
        $this->property_name = $prop_name;
        $this->filter_params = [];
    }

    /**
     * 
     * @param string $filter_name
     * @return  \Filters\IFilterParams
     */
    public function get(string $filter_name) {
        return array_key_exists($filter_name, $this->filter_params) ? $this->filter_params[$filter_name] : null;
    }

    public function set(\Filters\IFilterParams $value): \Filters\IFilterParamSet {
        $this->filter_params[$value->get_filter_name()] = $value;
        return $this;
    }

    public function exists(string $filter_name): bool {
        return array_key_exists($filter_name, $this->filter_params);
    }

    public function get_property_name(): string {
        return $this->property_name;
    }

    /**
     * 
     * @param string $property_name
     * @return \static
     */
    public static function F(string $property_name = \Filters\IParamPool::DEFAULT_PROP_ID) {
        return new static($property_name);
    }
    
    public function reset(string $filter_name = null): \Filters\IFilterParamSet {
        if ($filter_name) {
            if (array_key_exists($filter_name, $this->filter_params)) {
                unset($this->filter_params[$filter_name]);
            }
        } else {
            $this->filter_params = [];
        }
        return $this;
    }

}
