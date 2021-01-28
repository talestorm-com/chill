<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

final class FilterManager {

    /** @var FilterManager */
    private static $instance;

    /** @var IFilter[] */
    protected $filter_instances = [];

    protected function __construct() {
        static::$instance = $this;
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

    public function apply_chain($value, $filters, IFilterParamSet $params = null) {
        $filters = is_array($filters) ? $filters : explode(",", $filters);
        $rvalue = $value;
        foreach ($filters as $filter_p) {
            $filter_name = trim($filter_p);
            if ($filter_name && mb_strlen($filter_name,'UTF-8')) {
                $rvalue = $this->apply_filter_to_var($filter_name, $rvalue, $params ? $params->get($filter_name) : null);
            }
        }
        return $rvalue;
    }

    protected function apply_filter_to_var(string $filter_name, $value, IFilterParams $params = null) {
        $filter_instance = $this->get_filter_instance($filter_name);
        return $filter_instance->apply($value, $params);
    }

    /**
     * applies one filter to value
     * @param mixed $value
     * @param string $filter_name
     * @param \Filters\IFilterParams $params
     * @return mixed
     */
    public function apply_filter($value, string $filter_name, IFilterParams $params = null) {
        return $this->apply_filter_to_var($filter_name, $value, $params);
    }

    /**
     * applies filters to aray of values
     * @param array $input
     * @param array $filters
     * @param \Filters\IParamPool $params
     */
    public function apply_filter_array(array $input, array $filters, IParamPool $params = null) {
        $result = [];
        foreach ($filters as $field_name => $filter_set) {
            $result[$field_name] = $this->apply_chain(
                    (array_key_exists($field_name, $input) ? $input[$field_name] : EmptyValue::F()), $filter_set, ($params ? $params->get_param_set_for_property($field_name) : null)
            );
        }
        return $result;
    }

    /**
     * applies filters to datamap
     * @param \DataMap\IDataMap $input
     * @param array $filters
     * @param \Filters\IParamPool $params
     * @return array
     */
    public function apply_filter_datamap(\DataMap\IDataMap $input, array $filters, IParamPool $params = null) {
        $result = [];
        foreach ($filters as $field_name => $filter_set) {
            $result[$field_name] = $this->apply_chain(
                    ($input->exists($field_name) ? $input->get($field_name) : EmptyValue::F()), $filter_set, ($params ? $params->get_param_set_for_property($field_name) : null)
            );
        }
        return $result;
    }

    protected function get_filter_instance(string $filter_name): IFilter {
        if (!array_key_exists($filter_name, $this->filter_instances)) {
            $filter_class = \Helpers\Helpers::ref_classs_to_root(__NAMESPACE__ . "\\classes\\{$filter_name}Filter");
            $this->filter_instances[$filter_name] = $this->instantiate_filter($filter_class);
        }
        return $this->filter_instances[$filter_name];
    }

    protected function instantiate_filter(string $filter_class): IFilter {
        if (class_exists($filter_class)) {
            if (\Helpers\Helpers::class_implements($filter_class, IFilter::class)) {
                if (\Helpers\Helpers::class_inherits($filter_class, AbstractFilter::class)) {
                    return $filter_class::F();
                }
            }
        }
        echo "<br>filter_exists:" . (class_exists($filter_class) ? "true" : "false") . "<br>";
        echo "<br>filter_implements:" . (\Helpers\Helpers::class_implements($filter_class, IFilter::class) ? "true" : "false") . "<br>";
        echo "<br>" . IFilter::class . "<br>";
        var_dump(class_implements($filter_class));
        FilterError::RF("filter class `%s` not found or not conforms IFilter", $filter_class);
    }

    /**
     * throws error on first invalid value
     * @param array $input
     */
    public function raise_array_error(array $input) {
        foreach ($input as $key => $value) {
            if ($value instanceof Value) {
                FilterError::RF("field `%s`: %s", $key, $value->message);
            }
        }
    }

    /**
     * checks when all values is ok
     * @param array $input
     */
    public function is_values_ok(array $input): bool {
        foreach ($input as $value) {
            if ($value instanceof Value) {
                return false;
            }
        }
        return true;
    }

}
