<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common_accessors;

trait TCommonImport {

    protected function t_common_import_fill_data(array $clear_data, IFilterValueResolver $resolver = null) {
        foreach ($clear_data as $field_name => $field_value) {
            if (property_exists($this, $field_name)) {
                if (\Filters\Value::is($field_value)) {
                    /* @var $field_value \Filters\Value */
                    if ($resolver) {
                        $result = $resolver->resolve_value($field_name, $field_value);
                        if ($result && is_object($result) && ( $result instanceof \Exception)) {
                            throw $result;
                        }
                        $this->$field_name = $result;
                    } else {
                        ImportError::R($field_value->message);
                    }
                } else {
                    $this->$field_name = $field_value;
                }
            } else {
                $method = "t_common_import_set_value_for_field_{$field_name}";
                if (method_exists($this, $method)) {
                    if (\Filters\Value::is($field_value)) {
                        /* @var $field_value \Filters\Value */
                        if ($resolver) {
                            $result = $resolver->resolve_value($field_name, $field_value);
                            $this->$method($result);
                        } else {
                            $this->$method($field_value);
                        }
                    } else {
                        $this->$method($field_value);
                    }
                }
            }
        }
    }

    public function import_props_datamap(\DataMap\IDataMap $data, IFilterValueResolver $resolver = null,string $mode=null) {
        $filters = $this->t_common_import_get_filters_for_mode($mode);
        $params = \Filters\params\ArrayParamBuilder::B($this->t_common_import_get_filter_params_for_mode($mode), false);
        $clear_data = \Filters\FilterManager::F()->apply_filter_datamap($data, $filters, $params);
        $this->t_common_import_fill_data($clear_data, $resolver);
        $this->t_common_import_after_import();
    }

    public function import_props(array $data, IFilterValueResolver $resolver = null,string $mode = null) {
        $filters = $this->t_common_import_get_filters_for_mode($mode);
        $params = \Filters\params\ArrayParamBuilder::B($this->t_common_import_get_filter_params_for_mode($mode), false);
        $clear_data = \Filters\FilterManager::F()->apply_filter_array($data, $filters, $params);
        $this->t_common_import_fill_data($clear_data, $resolver);
        $this->t_common_import_after_import();
    }

    protected function t_common_import_after_import() {
        
    }

    protected function t_common_import_set_value_for_field_example_field($value) {
        
    }

    protected function t_common_import_get_filters(): array {
        return [];
    }

    protected function t_common_import_get_filter_params(): array {
        return [];
    }

    protected function t_common_import_get_filter_params_for_mode(string $mode = null): array {
        if ($mode === null) {
            return $this->t_common_import_get_filter_params();
        }
        $mode_method = "t_common_import_get_filters_params_for_{$mode}";
        if (method_exists($this, $mode_method)) {
            return $this->$mode_method();
        }
        ImportError::RF("no filter params method `%s` in `%s`", $mode_method, __CLASS__);
    }

    protected function t_common_import_get_filters_params_for_Testmode() {
        return [];
    }

    protected function t_common_import_get_filters_for_mode(string $mode = null) {
        if ($mode === null) {
            return $this->t_common_import_get_filters();
        }
        $mode_method = "t_common_import_get_filters_for_{$mode}";
        if (method_exists($this, $mode_method)) {
            return $this->$mode_method();
        }
        ImportError::RF("no filters method `%s` in `%s`", $mode_method, __CLASS__);
    }

    protected function t_common_import_get_filters_for_Testmode() {
        return [];
    }

}
