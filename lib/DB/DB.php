<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DB;

final class DB implements IDBAdapter {

    use \common_accessors\TCommonAccess;

    /** @var DB[] */
    private static $instances = [];
    private $_instance_id;
    private $config_key = null;

    /** @var \PDO */
    private $pdo = null;

    private function __construct($config_key) {
        try {
            $this->config_key = $config_key;
            $db_cnf = \Config\Config::F()->DB->get_default();
            $this->pdo = new \PDO($db_cnf->dsn, $db_cnf->user_name, $db_cnf->password, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    //\PDO::ATTR_EMULATE_PREPARES=>false,
            ]);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            static::$instances[$this->instance_id()] = $this;
        } catch (\Throwable $e) {
            throw new \Exception('database connection error');
        }
    }

    /**
     * 
     * @return \static
     */
    public static function F($instance_id = "default"): DB {
        if (array_key_exists($instance_id, static::$instances) && (static::$instances[$instance_id] instanceof DB)) {
            return static::$instances[$instance_id];
        }
        return new static($instance_id);
    }

    //<editor-fold defaultstate="collapsed" desc="singleton locks">
    private function __clone() {
        ;
    }

    private function __sleep() {
        ;
    }

    private function __wakeup() {
        ;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="IDBAdapter">   

    public function Rollback(): IDBAdapter {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        } else {
            throw new \PDOException("No transaction");
        }
        return $this;
    }

    public function beginTransaction(isolation\IIsolationLevel $isolation_level = null): IDBAdapter {
        if ($isolation_level) {
            $this->query("SET TRANSACTION ISOLATION LEVEL {$isolation_level->get_value()};");
        }
        $result = $this->pdo->beginTransaction();
        if (!$result) {
            $err = $this->pdo->errorInfo();
            $msg = $err && is_array($err) && isset($err[2]) ? $err[2] : "Error on start transaction";
            throw new \PDOException($msg);
        }
        return $this;
    }

    public function commit(): IDBAdapter {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        } else {
            throw new \PDOException("No transaction");
        }
        return $this;
    }

    public function exec(string $sql, array $params = [], &$row_count = null): IDBAdapter {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        is_null($row_count) ? false : $row_count = $statement->rowCount();
        return $this;
    }

    public function execi(string $sql, array $params = []): int {
        $rc = 0;
        $this->exec($sql, $params, $rc);
        return intval($rc);
    }

    public function getLastInsertId() {
        return $this->pdo->lastInsertId();
    }

    public function getLastInsertIdI(): int {
        return intval($this->getLastInsertId());
    }

    public function inTranscation(): bool {
        return $this->pdo->inTransaction();
    }

    public function query(string $sql, array $params = []): \PDOStatement {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    public function queryAll(string $sql, array $params = []): array {
        $statement = $this->query($sql, $params);
        return $statement->fetchAll();
    }

    public function queryAllIndex(string $sql, array $params = []): array {
        $statement = $this->query($sql, $params);
        return $statement->fetchAll(\PDO::FETCH_NUM);
    }

    public function queryRow(string $sql, array $params = []) {
        $statement = $this->query($sql, $params);
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    }

    public function queryRowIndex(string $sql, array $params = []) {
        $statement = $this->query($sql, $params);
        $result = $statement->fetch(\PDO::FETCH_NUM);
        $statement->closeCursor();
        return $result;
    }

    public function queryScalar(string $sql, array $params = [], int $index = 0) {
        $row = $this->queryRowIndex($sql, $params);
        if ($row && is_array($row) && array_key_exists($index, $row)) {
            return $row[$index];
        }
        return null;
    }

    public function queryScalari(string $sql, array $params = [], int $index = 0): int {
        return intval($this->queryScalar($sql, $params, $index));
    }

    public function get_affected_rows(): int {
        return $this->queryScalari("SELECT ROW_COUNT();", []);
    }

    public function get_found_rows(): int {
        return $this->queryScalari("SELECT FOUND_ROWS();", []);
    }

    public function instance_id(): string {
        if (!$this->_instance_id) {
            $this->_instance_id = $this->config_key; // . md5(__METHOD__);
        }
        return $this->_instance_id;
    }

    //</editor-fold>
}
