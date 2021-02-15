<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Sort;

use \ADVTable\Data\IData,
    \ADVTable\Data\PostData,
    \ADVTable\Util\TAccess;

/**
 * @property string $frontSortColumn
 * @property string $sortColumn
 * @property string $sortDirection
 * @property integer $fronSortDirection
 * @property boolean $valid
 * @property Array $columnAliases
 * @property string $SQL
 * @property string $tokens_separator
 */
abstract class AbstractSort {

    use TAccess;

    CONST SORT_TOKEN = null;
    CONST SORT_DIRECTION_TOKEN = null;

    /** @var Array */
    protected $column_aliases;
    protected $_frontColumn;
    protected $_frontDir;
    protected $tokens_separator = ',';

    //<editor-fold defaultstate="collapsed" desc="ts_accessors">
    protected function __get__tokens_separator() {
        return $this->tokens_separator;
    }

    protected function __set__tokens_separator($x) {
        $this->tokens_separator = $x;
        return $this;
    }

    //</editor-fold>

    /** @var IData */
    protected $idata;

    /*
     * $columnAliases = map to match column in surt token with column in query
     * Like  ['fontEndCol'=>'A.column'] 
     */

    /**
     * 
     * @param array $columnAliases
     */
    public function __construct(IData $idata = null, Array $columnAliases = null) {
        $this->column_aliases = is_array($columnAliases) ? $columnAliases : null;
        $this->idata = $idata ? $idata : PostData::F();
        $this->init();
    }

    /**
     * 
     * @return \static
     */
    public function init() {
        $this->_frontColumn = $this->idata->getPath($this->getSortToken(), null);
        $this->_frontDir = $this->idata->getPath($this->getSortDirToken(), null);
        $this->_frontDir = is_numeric($this->_frontDir) ? intval($this->_frontDir) : null;
        !is_null($this->_frontDir) ? ($this->_frontDir = $this->_frontDir <= 0 ? 0 : 1) : false;
        return $this;
    }

    protected function getSortDirToken() {
        return static::SORT_DIRECTION_TOKEN;
    }

    protected function getSortToken() {
        return static::SORT_TOKEN;
    }

    public function setColumnAliases(Array $colAlias) {
        $this->column_aliases = $colAlias;
    }

    /** @return string */
    protected function __get__frontSortColumn() {
        return $this->_frontColumn;
    }

    /** @return string */
    protected function __get__sortColumn() {
        return is_array($this->column_aliases) && array_key_exists($this->_frontColumn, $this->column_aliases) ? $this->column_aliases[$this->_frontColumn] : null;
    }

    /** @return string */
    protected function __get__sortDirection() {
        return $this->_frontDir === 1 ? 'DESC' : ($this->_frontDir === 0 ? 'ASC' : null);
    }

    /** @return integer */
    protected function __get__fronSortDirection() {
        return $this->_frontDir;
    }

    /** @return boolean */
    protected function __get__valid() {
        return !is_null($this->_frontColumn) && !is_null($this->_frontDir) && is_array($this->column_aliases) && array_key_exists($this->_frontColumn, $this->column_aliases);
    }

    /** @return Array */
    protected function __get__columnAliases() {
        return $this->column_aliases;
    }

    protected function __set__columnAliases(array $v) {
        $this->column_aliases = $v;
        return $this;
    }

    protected function __get__SQL() {
        if ($this->valid) {

            $ma = explode($this->tokens_separator, $this->sortColumn);
            if (count($ma) > 1) {
                $mm = [];
                foreach ($ma as $col_name) {
                    if (preg_match("/(^|\s)(asc|desc)(\s|$)/i", $col_name)) {
                        $mm[] = "{$col_name}";
                    } else {
                        $mm[] = "{$col_name} {$this->sortDirection}";
                    }
                }
                return " ORDER BY " . implode(",", $mm);
            }            
            return $this->valid ? " ORDER BY {$this->sortColumn} {$this->sortDirection} " : '';
        }
        return '';
    }

    /**
     * 
     * @param IData $i
     * @param array $aliases
     * @return \static
     */
    public static function F(IData $i = null, Array $aliases = null) {
        return new static($i, $aliases);
    }

}
