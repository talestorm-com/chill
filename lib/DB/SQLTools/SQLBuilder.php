<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DB\SQLTools;

/**
 * @property integer $counter  текущий счетчик
 * @property integer $c    текущий счетчик
 * @property string[] $sql_array  массив sq комманд
 * @property string[] $li_sql_array ссылка на массив sql комманд
 * @property string $sql   строка запроса
 * @property array $params массив параметров
 * @property array $li_params ссылка на массив параметров 
 * @property integer $command_length
 * @property boolean $empty
 * @property \DB\IDBAdapter $adapter
 */
class SQLBuilder {

    use \common_accessors\TCommonAccess;

    protected $_counter;
    protected $_params;
    protected $_sql;

    /** @var \DB\IDBAdapter $connection */
    protected $connection;

    //<editor-fold defaultstate="collapsed" desc="getters">
    protected function __get__counter() {
        return $this->_counter;
    }

    protected function __get__c() {
        return $this->_counter;
    }

    protected function __get__params() {
        return $this->_params;
    }

    protected function &__get__li_params() {
        return $this->_params;
    }

    protected function &__get__li_sql_aray() {
        return $this->_sql;
    }

    protected function __get__sql_array() {
        return $this->_sql;
    }

    protected function __get__sql() {
        return implode("\n", $this->_sql);
    }

    protected function __get__command_length() {
        return count($this->_sql);
    }

    protected function __get__empty() {
        return count($this->_sql) ? false : true;
    }

    protected function __get__adapter() {
        return $this->get_adapter();
    }

    //</editor-fold>

    public function push($sql) {
        $this->_sql[] = $sql;
        return $this;
    }

    public function push_params(array $params) {
        $this->_params = array_merge($this->_params, $params);
        return $this;
    }

    public function push_param($param_name, $param_value, $tix = true) {
        if ($tix && array_key_exists($param_name, $this->_params)) {
            SQLBuilderError::RF("param `%s` alredy exists in builder buffer", $param_name);
        }
        $this->_params[$param_name] = $param_value;
        return $this;
    }

    public function prepend($sql) {
        if (is_array($sql)) {
            return $this->prepend_array($sql);
        }
        $this->_sql = array_merge([$sql], $this->_sql);
        return $this;
    }

    public function prepend_array(array $sql) {
        $this->_sql = array_merge($sql, $this->_sql);
        return $this;
    }

    public function inc_counter() {
        $this->_counter++;
        return $this;
    }

    /**
     * Вып в транзакции.
     * 
     * @param string $rid_var  имя sql сессионки для чтения (c <b>собакой</b>). Должна вернуть IntMore0.
     * @throws \Exception
     */
    public function execute_transact($rid_var = null) {
        $trans = $this->connection->beginTransaction();
        $ret_var = null;
        try {
            $ret_var = $this->execute($rid_var);
            $trans->commit();
        } catch (\Exception $e) {
            $trans->rollback();
            throw $e;
        }
        return $ret_var;
    }

    /**
     * 
     * @param array $rid_vars   array [$var=>[filters],]
     * @return string[]
     * @throws \Exception
     */
    public function execute_transact_ret_vars(array $rid_vars) {
        $trans = $this->connection->beginTransaction();
        $ret_vars = [];
        try {
            $this->execute();
            try {
                foreach ($rid_vars as $rid_var => $rid_rules) {
                    $ret_vars[$rid_var] = \Filters\FilterManager::F()->apply_chain($this->connection->queryScalar("SELECT {$rid_var}"), $rid_rules);
                }
                \Filters\FilterManager::F()->raise_array_error($ret_vars);
            } catch (\Throwable $ee) {
                SQLBuilderError::R("RidRecoverError");
            }
            $trans->commit();
        } catch (\Exception $e) {
            $trans->rollback();
            throw $e;
        }
        return $ret_vars;
    }

    public function execute_transact_ret_str($rid_var) {
        $trans = $this->connection->beginTransaction();
        $ret_var = null;
        try {
            $ret_var = $this->execute_ret_str($rid_var);
            $trans->commit();
        } catch (\Exception $e) {
            $trans->rollback();
            throw $e;
        }
        return $ret_var;
    }

    /**
     * вып без транзакции (транзакция внешняя)
     * @param string $rid_var  если передан - будет прочитана и возвращена сессионная переменная с таки именем (IntMore0)
     * @return integer|null
     */
    public function execute($rid_var = null) {
        $ret_var = null;
        $this->empty ? SQLBuilderError::R("RequestIsEmpty!") : false;
        $this->connection->exec($this->sql, $this->params);
        //\Yii::app()->db->createCommand($this->sql)->execute($this->params);
        \DB\errors\MySQLWarn::F($this->connection);
        if ($rid_var) {
            $ret_var = $this->connection->queryScalari("SELECT {$rid_var}");
            //$ret_var = intval(\Yii::app()->db->createCommand("SELECT {$rid_var}")->queryScalar());
            $ret_var ? FALSE : SQLBuilderError::R("RidRecoverError");
        }
        return $ret_var;
    }

    /**
     * вып без транзакции (транзакция внешняя)
     * @param string $rid_var  если передан - будет прочитана и возвращена сессионная переменная с таки именем (IntMore0)
     * @return integer|null
     */
    public function execute_ret_str($rid_var) {
        $ret_var = null;
        $this->empty ? SQLBuilderError::R("RequestIsEmpty!") : false;
        $this->connection->exec($this->sql, $this->params);
        //\Yii::app()->db->createCommand($this->sql)->execute($this->params);
        \DB\errors\MySQLWarn::F($this->connection);
        //\SQLTools\Errors\SQLWarn::F();
        if ($rid_var) {            
            $ret_var = $this->connection->queryScalar("SELECT {$rid_var}");
            //$ret_var = trim(\Yii::app()->db->createCommand("SELECT {$rid_var}")->queryScalar());
            $ret_var && mb_strlen($ret_var,'UTF-8') ? FALSE : SQLBuilderError::R("RidRecoverError");
        }
        return $ret_var;
    }

    protected function __construct(\DB\IDBAdapter $connection = null) {
        $this->_counter = 0;
        $this->_params = [];
        $this->_sql = [];
        $this->connection = $connection ? $connection : \DB\DB::F();
    }

    public function get_adapter(): \DB\IDBAdapter {
        return $this->connection;
    }

    /**
     * 
     * @param \DB\IDBAdapter $connection
     * @return \DB\SQLTools\SQLBuilder
     */
    public static function F(\DB\IDBAdapter $connection = null): SQLBuilder {
        return new static($connection);
    }

}
