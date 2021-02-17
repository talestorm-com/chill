<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

/**
 * apply dataMap to numerical-indexed array
 * keys must be provided separately by <b>set_keys</b>
 */
class CommonDataMapIndex extends AbstractDataMap {

    protected $keys = [];

    protected function _data_map_read_only(): bool {
        return false;
    }

    protected function on_instance_created() {
        $re = [];
        $this->rebind($re);
    }

    protected static function _data_map_singleton(): bool {
        return false;
    }

    protected function _data_map_can_rebind(): bool {
        return true;
    }

    /**
     * 
     * @param array $keys
     * @return \DataMap\IDataMap
     */
    public function set_keys(array $keys): IDataMap {
        $this->keys = [];
        $c = 0;
        foreach ($keys as $key) {
            $this->keys[$key] = $c;
            $c++;
        }
        return $this;
    }

    protected function prepare_key(string $key) {
        return array_key_exists($key, $this->keys) ? $this->keys[$key] : 99999;
    }

    public function remove(string $key): IDataMap {

        DataMapError::RF("cant remove key `%s` in class `%s` - DataMap is read only", $key, get_called_class());
    }
    
    

}
