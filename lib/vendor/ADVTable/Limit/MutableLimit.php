<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Limit;

/**
 * @property string $pageToken
 * @property string $perPageToken
 */
class MutableLimit extends AbstractLimit {

    protected $_pageToken;
    protected $_perToken;

    protected function getPageToken() {
        return $this->_pageToken;
    }

    protected function getPerPageToken() {
        return $this->_perToken;
    }

    /**
     * 
     * @param type $page
     * @param type $perpage
     * @return \ADVTable\Limit\MutableLimit
     */
    public function setTokens($page, $perpage) {
        $this->_pageToken = $page;
        $this->_perToken = $perpage;
        return $this;
    }

    protected function __set__pageToken($v) {
        $this->_pageToken = $v;
        return $this;
    }

    protected function __set__perPageToken($v) {
        $this->_perToken = $v;
        return $this;
    }

}
