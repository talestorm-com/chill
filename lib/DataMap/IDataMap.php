<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

interface IDataMap {

    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * 
     * @param string $key
     * @param array $filters
     * @return mixed
     */
    public function get_filtered(string $key, array $filters = [], \Filters\IFilterParamSet $params = null);

    public function get_filtered_def(string $key, array $filters = [], \Filters\IParamPool $parampool = null);

    /**
     * 
     * @param array $source
     * @return IDataMap
     */
    public function rebind(array &$source): IDataMap;

    /**
     * 
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * 
     * @param string $key
     * @return IDataMap
     * @throws DataMapError
     */
    public function remove(string $key): IDataMap;

    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return IDataMap
     * @throws DataMapError
     */
    public function set(string $key, $value): IDataMap;

    /**
     * retrns aray with all data
     * @return Array Description
     */
    public function get_all_cloned(): Array;
    
    //public function filtered_with_default(string $key, array $filters=[],$default=null);
    
   
}
