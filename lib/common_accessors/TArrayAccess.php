<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common_accessors;

trait TArrayAccess {

    protected function t_array_access_get_indexed_prop() {
        return 'items';
    }

    public function offsetExists($offset): bool {
        $pn = $this->t_array_access_get_indexed_prop();
        return array_key_exists($offset, $this->$pn);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function value_get($offset, $default = null) {
        $pn = $this->t_array_access_get_indexed_prop();
        return array_key_exists($offset, $this->$pn) ? $this->$pn[$offset] : $default;
    }

    public function offsetSet($offset, $value): void {
        $pn = $this->t_array_access_get_indexed_prop();
        $this->$pn[$offset] = $value;
    }

    public function offsetUnset($offset): void {
        if ($this->offsetExists($offset)) {
            $pn = $this->t_array_access_get_indexed_prop();
            unset($this->$pn[$offset]);
        }
    }

}
