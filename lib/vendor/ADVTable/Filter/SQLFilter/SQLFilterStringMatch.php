<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Filter\SQLFilter;

/**
 * Фильтр точного совпадения строки без LIKE - регулярок
 */
class SQLFilterStringMatch extends SQLFilter {

    CONST LOC_ANYWHERE = 0x00;
    CONST LOC_START = 0x01;
    CONST LOC_END = 0x02;

    protected $loc = 0x00; //bitmask 0x00 - anywhere 0x01 - 

    public function getSQL(array &$out, array &$params, &$counter) { 
        if ($this->valid) {
            $counter++;
            $out[] = "({$this->columnName} = :P{$counter})";
            $params[":P{$counter}"] =  $this->value ;
            $counter++;
        }
    }

    public function isValid() {
        return $this->value ? true : false;
    }

    public function prepareValues() {
        $this->value = trim($this->value);
        $this->value = mb_strlen($this->value, 'UTF-8') ? $this->value : null;        
    }

}
