<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\params;

class ArrayParamBuilder implements \Filters\IFilterParamBuilder {

    protected $source_array;
    protected $one_level = false;

    public function build(): \Filters\IParamPool {
        if ($this->one_level) {
            return $this->build_default_pool();
        }
        return $this->build_property_pool();
    }

    /**
     * * all filters for default property.
     * source array must be [filter_name=>[param_name=>param_value,...]]
     * @return FilterParamPool
     */
    protected function build_default_pool(): FilterParamPool {
        $result = FilterParamPool::F();
        $one_set = $result->get_param_set_for_property();
        foreach ($this->source_array as $filter_name => $filter_params) {
            $one_filter_params = FilterParams::F($filter_name);
            foreach ($filter_params as $name => $value) {
                $one_filter_params->set($name, $value);
            }
            $one_set->set($one_filter_params);
        }
        return $result;
    }

    /**
     * sourec array must be ['property'=>['fielter'=>[param_name=>param_value],filter=>[]]]
     * @return FilterParamPool Description
     */
    protected function build_property_pool(): FilterParamPool {
        $result = FilterParamPool::F();
        foreach ($this->source_array as $prop_name => $prop_filters) {
            $filter_set = $result->get_param_set_for_property($prop_name);
            foreach ($prop_filters as $filter_name => $filter_params) {
                $filter_params = FilterParams::F($filter_name);
                foreach ($filter_params as $key => $value) {
                    $filter_params->set($key, $value);
                }
                $filter_set->set($filter_params);
            }
        }
        return $result;
    }

    public function __construct(array $prop_filter_params, bool $for_default_prop = false) {
        $this->source_array = $prop_filter_params;
        $this->one_level = $for_default_prop;
    }

    /**
     * 
     * @param array $prop_filter_params
     * @param bool $for_default_prop
     * @return \static
     */
    public static function F(array $prop_filter_params, bool $for_default_prop = false) {
        return new static($prop_filter_params, $for_default_prop);
    }

    /**
     * 
     * @param array $prop_filter_params
     * @param bool $for_default_prop
     * @return \Filters\IParamPool
     */
    public static function B(array $prop_filter_params, bool $for_default_prop = false) {
        return static::F($prop_filter_params, $for_default_prop)->build();
    }

}
