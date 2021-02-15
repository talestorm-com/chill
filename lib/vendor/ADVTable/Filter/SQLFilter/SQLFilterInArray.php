<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Filter\SQLFilter;

/**
 * @property string $operand
 */
class SQLFilterInArray extends SQLFilter {
    

    public function getSQL(array &$out, array &$params, &$counter) {
        if ($this->isValid()) {
            $counter++;            
            $out[]=("{$this->columnName} IN ({$this->listParams($params,$counter)}) ");
            $counter ++;
        }
    }
    
    protected function listParams(array &$params,&$counter){
        $pc = 0;
        $result=[];
        foreach($this->value as $one){
            $result[]=":P{$counter}inArray_{$pc}";
            $params[":P{$counter}inArray_{$pc}"]=$one;
            $pc++;
        }
        return implode(",", $result);
    }

    public function isValid() {
        return !is_null($this->value) && !is_null($this->columnName) && is_array($this->value) && count($this->value) ? true : false;
    }

    public function prepareValues() {
        if (!(is_array($this->value) && count($this->value))){
            $this->value = null;
        }        
    }

}
