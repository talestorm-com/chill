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
class SQLFilterNumericX100 extends SQLFilter {

    protected $operand = '=';

    public function getSQL(array &$out, array &$params, &$counter) {
        if ($this->isValid()) {
            $counter++;
            switch ($this->operand) {
                case "<":
                    $out[] = "({$this->columnName} < :P{$counter})";
                    break;
                case ">":
                    $out[] = "({$this->columnName} > :P{$counter})";
                    break;
                case ">=":
                case "=>":
                    $out[] = "({$this->columnName} >= :P{$counter})";
                    break;
                case "<=":
                case "=<":
                    $out[] = "({$this->columnName} <= :P{$counter})";
                    break;
                default :
                    $out[] = "({$this->columnName} = :P{$counter})";
                    break;
            }
            $params[":P{$counter}"] = $this->value;
        }
    }

    public function isValid() {
        return !is_null($this->value) && !is_null($this->columnName) ? true : false;
    }

    public function prepareValues() {
        $m = [];
        if (preg_match("/(?P<operand>(?:>|<|=|>=|<=|=>|=<){0,1})(?P<val>-{0,1}[\d\.]{1,})/i", $this->value, $m)) {
            $this->operand = array_key_exists('operand', $m) ? $m['operand'] : '=';
            $this->value = is_numeric($m['val']) ? intval(round(floatval($m['val'])*100,0)) : null;
        } else {
            $this->value = null;
        }
    }

}
