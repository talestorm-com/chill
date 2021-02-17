<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Limit;

use \ADVTable\Data\IData;

/**
 * @property integer $page
 * @property integer $perpage
 * @property integer $offset
 * @property integer $limit 
 * @property string $MySqlLimit
 * @property boolean $valid
 */
class AbstractLimit {

    use \ADVTable\Util\TAccess;

    CONST PERPAGE_TOKEN = null;
    CONST PAGE_TOKEN = null;

    private $page;
    private $perpage;

    /** @var \ADVTable\Data\IData */
    protected $idata;

    public function __construct(IData $data = null) {
        $this->idata = $data ? $data : \ADVTable\Data\PostData::F();
        $this->init();
    }

    /**
     * 
     * @return \ADVTable\Limit\AbstractLimit
     */
    public function init() {
        $this->page = $this->idata->getPath($this->getPageToken(), null);
        $this->perpage = $this->idata->getPath($this->getPerPageToken(), null);
        $this->page = is_numeric($this->page) ? intval($this->page) : 0;
        $this->perpage = is_numeric($this->perpage) ? intval($this->perpage) : null;
        return $this;
    }

    protected function getPageToken() {
        return static::PAGE_TOKEN;
    }

    protected function getPerPageToken() {
        return static::PERPAGE_TOKEN;
    }

    public function setPage($x) {
        $this->page = abs(intVal($x));
    }

    /**
     * 
     * @param IData $data
     * @return \static
     */
    public static function F(IData $data = null) {
        return new static($data);
    }

    //<editor-fold defaultstate="collapsed" desc="getters">
    protected function __get__page() {
        return $this->page;
    }

    protected function __get__perpage() {
        return $this->perpage;
    }

    protected function __get__valid() {
        return (!is_null($this->page) && !is_null($this->perpage)) ? true : false;
    }

    protected function __get__offset() {
        return $this->valid ? $this->page * $this->perpage : null;
    }

    protected function __get__limit() {
        return $this->valid ? $this->perpage : null;
    }

    protected function __get__MySqlLimit() {
        return $this->valid ? " LIMIT {$this->limit} OFFSET {$this->offset} " : "";
    }

    protected function __set__page($x) {
        return $this->setPage($x);
    }

    //</editor-fold>
}
