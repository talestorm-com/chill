<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Filter\SQLFilter;

/**
 * @property string $mode
 * @property \DateTime $value1
 * @property \DateTime $value2
 * @property string $predicate
 */
class SQLFilterDate extends SQLFilter {

    CONST MODE_MATCH = "MATCH";
    CONST MODE_MORE = "MORE";
    CONST MODE_MOREEQ = "MOREEQ";
    CONST MODE_LESS = "LESS";
    CONST MODE_LESSEQ = "LESSEQ";
    CONST MODE_BETWEEN = "BETWEEN";

    protected static $allowed_modes = [
        "MATCH" => "=", "MORE" => ">", "MOREEQ" => ">=", "LESS" => "<", "LESSEQ" => "<=", "BETWEEN" => 1,
    ];
    protected $mode = null;
    protected $value1 = null;
    protected $value2 = null;

    public function getSQL(array &$out, array &$params, &$counter) {
        if ($this->valid) {
            if ($this->value1 && $this->value2) {
                if ($this->value1->getTimestamp() > $this->value2->getTimestamp()) {
                    $val = $this->value2;
                    $this->value2 = $this->value1;
                    $this->value1 = $val;
                }
            }
            $counter++;
            if ($this->mode !== static::MODE_BETWEEN) {
                $out[] = "(DATE({$this->columnName}) {$this->predicate} :P{$counter} )";
                $params[":P{$counter}"] = $this->value1->format('Y-m-d');
            } else {
                $out[] = "(DATE({$this->columnName}) BETWEEN :P{$counter} AND :PP{$counter} )";
                $params[":P{$counter}"] = $this->value1->format('Y-m-d');
                $params[":PP{$counter}"] = $this->value2->format('Y-m-d');
            }
            $counter++;
        }
    }

    public function isValid() {
        return $this->mode && $this->value1 && ($this->value2 || $this->mode !== static::MODE_BETWEEN);
    }

    public function prepareValues() {
        $this->value = is_array($this->value) ? $this->value : [];
        $this->mode = $this->getMode();
        $this->value1 = $this->readDate('v1');
        $this->value2 = $this->readDate('v2');
        return $this;
    }

    protected function getMode() {
        $mode = array_key_exists('m', $this->value) ? $this->value['m'] : null;
        return $mode && array_key_exists($mode, static::$allowed_modes) ? $mode : null;
    }

    protected function readDate($key) {
        if ($key && array_key_exists($key, $this->value) && $this->value[$key]) {
            try {
                return new \DateTime($this->value[$key]);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /** @return string */
    protected function __get__mode() {
        return $this->mode;
    }

    /** @return \DateTime */
    protected function __get__value1() {
        return $this->value1;
    }

    /** @return \DateTime */
    protected function __get__value2() {
        return $this->value2;
    }

    /** @return string */
    protected function __get__predicate() {
        return static::$allowed_modes[$this->mode];
    }

}
