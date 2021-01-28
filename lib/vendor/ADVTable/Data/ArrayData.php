<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Data;

class ArrayData extends AbstractData {

    protected static $instance = null;

    protected function bind() {
        //do nothing
    }

    public function rebind(Array $data) {
        $this->data = &$data;
        static::$instance = null; // убрать синглет
    }

}
