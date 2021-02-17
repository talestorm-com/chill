<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Sort;

/**
 * @property string $columnToken 
 * @property string  $dirToken 
 */
class MutableSort extends AbstractSort {

    protected $_ct;
    protected $_dt;

    protected function __get__columnToken() {
        return $this->_ct;
    }

    protected function __get__dirToken() {
        return $this->_dt;
    }

    protected function __set__dirToken($v) {
        $this->_dt = is_string($v) ? $v : null;
    }

    protected function __set__columnToken($v) {
        $this->_ct = is_string($v) ? $v : null;
    }

    protected function getSortToken() {
        return $this->_ct;
    }

    protected function getSortDirToken() {
        return $this->_dt;
    }

    /**
     * 
     * @param type $sortToken
     * @param type $orderToken
     * @return \static
     */
    public function setTokens($sortToken, $orderToken) {
        $this->_ct = $sortToken;
        $this->_dt = $orderToken;
        return $this;
    }

}
