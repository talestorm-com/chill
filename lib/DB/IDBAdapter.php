<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DB;

interface IDBAdapter {

    /**
     * starts transaction
     * @param \DB\isolation\IIsolationLevel $isolation_level transaction isolation level
     * @return IDBAdapter instance to chain
     */
    public function beginTransaction(isolation\IIsolationLevel $isolation_level = null): IDBAdapter;

    /**
     * rollbacks current transaction
     * @return IDBAdapter instance to chain
     */
    public function Rollback(): IDBAdapter;

    /**
     * commits current transaction
     * @return IDBAdapter instace to chain
     */
    public function commit(): IDBAdapter;

    /**
     * @return boolean
     */
    public function inTranscation(): bool;

    /**
     * executes a query
     * @param string $sql   query to execute
     * @param array $params params for query
     * @param int $row_count refernce to affected row count
     * @return IDBAdapter instance to chain
     */
    public function exec(string $sql, array $params = [], &$row_count = null): IDBAdapter;

    /**
     * executes a query
     * @param string $sql query to execute
     * @param array $params params for query
     * @return int count of affected rows
     */
    public function execi(string $sql, array $params = []): int;

    /**
     * exec query and return PDOStatement to fetch rows
     * @param string $sql  query to execute
     * @param array $params  named params for query
     * @return \PDOStatement
     */
    public function query(string $sql, array $params = []): \PDOStatement;

    /**
     * exec a query and return them as <b>associative array</b>
     * @param string $sql query to execute
     * @param array $params named params for query
     * @return array
     */
    public function queryAll(string $sql, array $params = []): array;

    /**
     * exec a query and return them as <b>number-indexed array</b>
     * @param string $sql query to execute
     * @param array $params named params for query
     * @return array
     */
    public function queryAllIndex(string $sql, array $params = []): array;

    /**
     * queryng one row and returns it as <b>associative aray</b>
     * @param string $sql query to execute
     * @param array $params named params
     * @return Array|null Description
     */
    public function queryRow(string $sql, array $params = []);

    /**
     * queryng one row and returns it as <b>number-indexed aray</b>
     * @param string $sql query to execute
     * @param array $params named params
     * @return Array|null Description
     */
    public function queryRowIndex(string $sql, array $params = []);

    /**
     * exec query and return scalar value
     * @param string $sql query to execute
     * @param array $params named params for query
     * @param int $index index of value to return (default 0)
     * @return mixed
     */
    public function queryScalar(string $sql, array $params = [], int $index = 0);

    /**
     * exec query and return scalar value casted to int
     * @param string $sql $sql query to execute
     * @param array $params query to execute
     * @param int $index index of value to return (default 0)
     * @return int integer scalar
     */
    public function queryScalari(string $sql, array $params = [], int $index = 0): int;

    /**
     * returns last insert id
     * @return mixed Description
     */
    public function getLastInsertId();

    /**
     * returns intval of last insert id
     * @return int last insert id casted to int
     */
    public function getLastInsertIdI(): int;

    /**
     * returns  FOUND_ROWS() result
     * @return integer;
     */
    public function get_found_rows(): int;

    /**
     * returns  ROW_COUNT() result
     * @return int
     */
    public function get_affected_rows(): int;

    /**
     * returns unique instance_id
     */
    public function instance_id(): string;
}
