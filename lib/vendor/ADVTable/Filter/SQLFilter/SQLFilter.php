<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Filter\SQLFilter;

use \ADVTable\Util\TAccess;

/**
 * Класс преобразует данные фильтров в условия where
 * на вход - имя колонки, значение фильтра, набор условий (всех)
 * 1) Выбрать условие по имени колонки
 * 2) из Условия достать подходящий класс (по имени фильтра) иинстанцировать его
 * 3) в фильтре - проанализировать условие и создать фрагмент where
 * @property boolean $valid
 */
abstract class SQLFilter {

    use TAccess;

    protected $value;
    protected $columnName;
    protected $params;

    protected function __construct($value, $columnName, $params) {
        $this->value = $value;
        $this->columnName = $columnName;
        $this->params = $params;
        $this->prepareValues();
    }

    
    /**
     * Производит типкастинг и разбор параметров
     */
    abstract public function prepareValues();


    /**
     * преобразует значение фильтра в условие SQL
     */
    abstract public function getSQL(Array &$out, Array &$params, &$counter);

    /**
     * проверка значения фильтра на валидность
     */
    abstract public function isValid();

    //<editor-fold defaultstate="collapsed" desc="SQLFilterFactory">
    /**
     * 
     * @param type $columnName
     * @param type $filterValue
     * @param array $rules
     * @return SQLFilter
     */
    public static function FACTORY($columnName, $filterValue, Array $rules = null) {
       // die('FACTORY');
        $rules = is_array($rules) ? $rules : [];
        //var_dump($rules);die('I AM STUPID ');
        if (array_key_exists($columnName, $rules)) {
            $columnRules = static::mkArray($rules[$columnName]);
            if (is_array($columnRules)) {
                $class = trim(array_key_exists('filter', $columnRules) ? $columnRules['filter'] : ''); //имя класса фильтра
               // var_dump($class);die();
                $class = mb_strlen($class, 'UTF-8') ? $class : null;
                if ($class) {
                    $rename = array_key_exists('column', $columnRules) ? $columnRules['column'] : null; // к какой колоке в БД применять
                    $rename ? false : $rename = $columnName; //tckb yt jghtltktyj
                    $params = array_key_exists('params', $columnRules) ? $columnRules['params'] : null;
                    $fqcn = __NAMESPACE__ . "\\SQLFilter{$class}";
                    if (static::filterExists($fqcn)) {
                        return $fqcn::F($filterValue, $rename, $params);
                    }
                }
            }
        }
        return null;
    }

    protected static function filterExists($fqcn) {
        if (class_exists($fqcn)) {
            foreach (class_parents($fqcn) as $pclass) {
                if (trim($pclass, "\\/") === trim(__CLASS__, "\\/")) {
                    return true;
                }
            }
        }
        return false;
    }

    protected static function mkArray($in) {
        if (is_array($in)) {
            return $in;
        }
        $ina = explode(":", $in, 3);
        if (count($ina)) {
            return [
                'filter' => $ina[0],
                'column' => array_key_exists(1, $ina) && mb_strlen($ina[1], 'UTF-8') ? $ina[1] : null,
                'params' => array_key_exists(2, $ina) ? $ina[2] : null
            ];
        }
        return null;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    protected function __get__valid() {
        return $this->isValid();
    }

    //</editor-fold>

    /**
     * 
     * @param mixed $value
     * @param string $column
     * @param string $params
     * @return \static
     */
    public static function F($value, $column, $params = null) {
        return new static($value, $column, $params);
    }

}
