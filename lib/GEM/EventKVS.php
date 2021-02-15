<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GEM;

class EventKVS implements \ArrayAccess {

    protected $values = [];

    protected function __construct(array $data = null) {
        $this->values = [];
        if (is_array($data)) {
            $this->set_array($data);
        }
    }

    public function get($name, $default = null) {
        return array_key_exists($name, $this->values) ? $this->values[$name] : $default;
    }

    public function set($name, $value): EventKVS {
        $this->values[$name] = $value;
        return $this;
    }

    public function remove($name): EventKVS {
        if ($this->offsetExists($name)) {
            unset($this->values[$name]);
        }
        return $this;
    }

    public function set_array(array $data): EventKVS {
        foreach ($data as $key => $value) {
            $this->values[$key] = $value;
        }
        return $this;
    }

    /**
     * 
     * @param array $data
     * @return \GEM\EventKVS
     */
    public static function F(array $data): EventKVS {
        return new static($data);
    }

    public function offsetExists($offset): bool {
        return array_key_exists($offset, $this->values);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void {
        $this->values[$offset] = $value;
    }

    public function offsetUnset($offset): void {
        $this->remove($offset);
    }

}
