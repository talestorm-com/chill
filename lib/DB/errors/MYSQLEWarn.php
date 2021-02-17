<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DB\errors;

abstract class MYSQLEWarn {

    CONST COMMAND = null;

    public function __construct(\DB\IDBAdapter $connection) {
        $ewr = $connection->queryRow(static::COMMAND);
        if ($ewr && is_array($ewr) && count($ewr) && array_key_exists('Message', $ewr)) {
            throw new MySQLEWarnException($ewr['Message']);
        }
    }

    /**
     * 
     * @return \static
     */
    public static function F(\DB\IDBAdapter $connection) {
        return new static($connection);
    }

}
