<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

/**
 * трейт для делегирования датамапа
 */
trait TInternalDataMapProxy {

    protected function t_array_data_map_get_internal_map(): IDataMap {
        return $this->internal_data_map;
    }

    public function exists(string $key): bool {
        return $this->t_array_data_map_get_internal_map()->exists($key);
    }

    public function get(string $key, $default = null) {
        return $this->t_array_data_map_get_internal_map()->get($key, $default);
    }

    public function get_all_cloned(): array {
        return $this->t_array_data_map_get_internal_map()->get_all_cloned();
    }

    public function get_filtered(string $key, array $filters = array(), \Filters\IFilterParamSet $params = null) {
        return $this->t_array_data_map_get_internal_map()->get_filtered($key, $filters, $params);
    }

    public function get_filtered_def(string $key, array $filters = array(), \Filters\IParamPool $parampool = null) {
        return $this->t_array_data_map_get_internal_map()->get_filtered_def($key, $filters, $parampool);
    }

    public function rebind(array &$source): IDataMap {
        return $this->t_array_data_map_get_internal_map()->rebind($source);
    }

    public function remove(string $key): IDataMap {
        return $this->t_array_data_map_get_internal_map()->remove($key);
    }

    public function set(string $key, $value): IDataMap {
        return $this->t_array_data_map_get_internal_map()->set($key, $value);
    }

    /**
     * 
     * @return \ADVTable\Data\IData
     */
    public function transform_to_advt_idata() {
        return $this->t_array_data_map_get_internal_map()->transform_to_advt_idata();
    }

}
