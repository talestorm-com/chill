<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

/**
 * Description of ADVTIDataBridge
 *
 * @author eve
 */
class ADVTIDataBridge implements \ADVTable\Data\IData {

    /** @var IDataMap */
    private $map;

    public function get_map(): IDataMap {
        return $this->map;
    }

    public function __construct(IDataMap $map) {
        $this->map = $map;
    }

    /**
     * 
     * @param \DataMap\IDataMap $map
     * @return \DataMap\ADVTIDataBridge
     */
    public static function F(IDataMap $map):ADVTIDataBridge{
        return new static($map);
    }

    

    //put your code here
    public function exists($n) {
        return $this->map->exists($n);
    }

    public function get($n, $def = null) {
        return $this->map->get($n, $def);
    }

    public function getPath($path, $def = null) {
        $path_parts = explode(".", $path);
        $target_map = $this->map;
        for($i= 0;$i<count($path_parts)-1;$i++){
            $remap = $target_map->get_filtered($path_parts[$i], ["NEArray","DefaultEmptyArray"]);
            $target_map = CommonDataMap::F()->rebind($remap);
        }
        return $target_map->get($path_parts[count($path_parts)-1], $def);
        //\Errors\common_error::RF("%s is not supported", __METHOD__);
    }

    public function offsetExists($offset) {
        return $this->map->exists($offset);
    }

    public function offsetGet($offset) {
        return $this->map->get($offset, 0);
    }

    public function offsetSet($offset, $value) {
        return $this->map->set($offset, $value);
    }

    public function offsetUnset($offset) {
        $this->map->remove($offset);
    }

    public function pathExists($path) {
        $path_parts = explode(".", $path);
        $target_map = $this->map;
        for($i= 0;$i<count($path_parts)-1;$i++){
            $remap = $target_map->get_filtered($path_parts[$i], ["NEArray","DefaultEmptyArray"]);
            $target_map = CommonDataMap::F()->rebind($remap);
        }
        return $target_map->exists($path_parts[count($path_parts)-1]);
        
    }

    public function put($n, $v) {
        return $this->map->set($n, $v);
    }

    public function remove($n) {
        return $this->map->remove($n);
    }

}
