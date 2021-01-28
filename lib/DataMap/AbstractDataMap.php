<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

abstract class AbstractDataMap implements IDataMap {

    /** @var IDataMap[] */
    protected static $instances = [];

    /** @var Array */
    protected $data;

    /** @var Array */
    protected $failsafe_data;

    final protected function __construct() {
        $this->on_instance_created();
    }

    abstract protected function on_instance_created();

    abstract protected function _data_map_read_only(): bool;

    abstract protected static function _data_map_singleton(): bool;

    abstract protected function _data_map_can_rebind(): bool;

    protected function prepare_key(string $key) {
        return $key;
    }

    public function exists(string $key): bool {
        $key = $this->prepare_key($key);
        return (array_key_exists($key, $this->data) || (is_array($this->failsafe_data) && array_key_exists($key, $this->failsafe_data)));
    }

    public function get(string $key, $default = null) {
        $key = $this->prepare_key($key);
        return array_key_exists($key, $this->data) ?
                $this->data[$key] :
                ((is_array($this->failsafe_data) && array_key_exists($key, $this->failsafe_data)) ?
                $this->failsafe_data[$key] :
                $default);
    }

    public function rebind(array &$source): IDataMap {
        if (!is_array($this->data) || $this->_data_map_can_rebind()) {
            $this->data = &$source;
        } else {
            DataMapError::RF("cant rebind data map in `%s` - DataMap under singe_bind restriction", get_called_class());
        }
        return $this;
    }

    public function remove(string $key): IDataMap {
        if (!$this->_data_map_read_only()) {
            $key = $this->prepare_key($key);
            if (array_key_exists($key, $this->data)) {
                unset($this->data[$key]);
            }
        } else {
            DataMapError::RF("cant remove key `%s` in class `%s` - DataMap is read only", $key, get_called_class());
        }
        return $this;
    }

    public function set(string $key, $value): IDataMap {
        if (!$this->_data_map_read_only()) {
            $key = $this->prepare_key($key);
            $this->data[$key] = $value;
        } else {
            DataMapError::RF("cant set value for key `%s` in class `%s` - DataMap is read only", $key, get_called_class());
        }
        return $this;
    }

    public function get_all_cloned(): Array {
        return array_merge($this->data, (is_array($this->failsafe_data) ? $this->failsafe_data : []));
    }

    /**
     * @return IDataMap
     */
    public static function F(): IDataMap {
        if (static::_data_map_singleton()) {
            $instance_key = md5(get_called_class());
            if (!array_key_exists($instance_key, static::$instances) || !(static::$instances[$instance_key] instanceof IDataMap)) {
                static::$instances[$instance_key] = new static();
            }
            return static::$instances[$instance_key];
        } else {
            return new static();
        }
    }

    /**
     * 
     * @param string $key
     * @param array $filters
     * @param \Filters\IParamPool $parampool
     * @return mixed
     */
    public function get_filtered_def(string $key, array $filters = array(), \Filters\IParamPool $parampool = null) {
        return $this->get_filtered($key, $filters, $parampool && $parampool->has_params_for_property() ? $parampool->get_param_set_for_property() : null);
    }

    /**
     * 
     * @param string $key
     * @param array $filters
     * @param \Filters\IFilterParamSet $params
     * @return mixed
     */
    public function get_filtered(string $key, array $filters = array(), \Filters\IFilterParamSet $params = null) {
        return \Filters\FilterManager::F()->apply_chain($this->get($key), $filters, $params);
    }
    
    /**
     * 
     * @param string $key
     * @param array $filters
     * @param type $default
     * @return mixed
     */
    public function filtered_with_default(string $key, array $filters=[],$default=null){
        $result =  \Filters\FilterManager::F()->apply_chain($this->get($key), $filters, null);
        return \Filters\Value::is($result)?$default:$result;
    }

  

}
