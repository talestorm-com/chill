<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Filter;

/**
 * @property string $filterToken
 */
class MutableFilter extends AbstractFilter {

    protected $_filterToken;

    protected function getFiltersToken() {
        return $this->_filterToken;
    }

    public function setFilterToken($x) {
        $this->_filterToken = $x;
        return $this->init();
    }

    protected function __get__filterToken() {
        return $this->filterToken;
    }

    protected function __set__filterToken($x) {
        return $this->setFilterToken($x);
    }

}
