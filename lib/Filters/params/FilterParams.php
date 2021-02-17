<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\params;

/**
 * common implementation of filter params
 * @property string $filter_name  mnemonic name of filter
 */
class FilterParams implements \Filters\IFilterParams {

    use \common_accessors\TCommonAccess;

    /** @var array  key-value list of filter props */
    protected $items;

    /** @var string */
    protected $name;

    protected function __get__filter_name() {
        return $this->name;
    }

    public function exists(string $param_name): bool {
        return array_key_exists($param_name, $this->items);
    }

    public function get(string $param_name, $default = null) {
        return array_key_exists($param_name, $this->items) ? $this->items[$param_name] : $default;
    }

    public function set(string $param_name, $param_value): \Filters\IFilterParams {
        $this->items[$param_name] = $param_value;
        return $this;
    }

    public function get_filter_name(): string {
        return $this->name;
    }

    public final function __construct(string $filter_name) {
        $this->name = $filter_name;
        $this->items = [];
    }

    /**
     * 
     * @param string $filter_name     
     * @return \static
     */
    public static function F(string $filter_name) {
        return new static($filter_name);
    }

}
