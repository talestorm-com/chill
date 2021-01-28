<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\params;

class FilterParamPool implements \Filters\IParamPool {

    /** @var \Filters\IFilterParamSet[] */
    protected $props;

    public function __construct() {
        $this->props = [];
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function exists(string $filter_name, string $prop_name = IParamPool::DEFAULT_PROP_ID): bool {
        if (array_key_exists($prop_name, $this->props)) {
            return $this->props[$prop_name]->exists($filter_name);
        }
        return false;
    }

    public function get_params_for(string $filter_name, string $prop_name = IParamPool::DEFAULT_PROP_ID): \Filters\IFilterParams {
        if (array_key_exists($prop_name, $this->props)) {
            return $this->props[$prop_name]->get($filter_name);
        }
        return null;
    }

    public function has_params_for_property(string $prop_name = IParamPool::DEFAULT_PROP_ID): bool {
        return array_key_exists($prop_name, $this->props);
    }

    public function set_params_for_prop_filter(\Filters\IFilterParams $params, string $prop_name = IParamPool::DEFAULT_PROP_ID): \Filters\IParamPool {
        if (!array_key_exists($prop_name, $this->props)) {
            $this->props[$prop_name] = FilterParamSet::F($prop_name);
        }
        $this->props[$prop_name]->set($params->get_filter_name(), $params);
    }

    public function set_params_for_property(\Filters\IFilterParamSet $param): \Filters\IParamPool {
        $this->props[$param->get_property_name()] = $param;
        return $this;
    }

    public function get_param_set_for_property(string $prop_name = \Filters\IParamPool::DEFAULT_PROP_ID): \Filters\IFilterParamSet {
        
        if (!array_key_exists($prop_name, $this->props)) {
            $this->props[$prop_name] = FilterParamSet::F($prop_name);
        }
        return $this->props[$prop_name];
    }

}
