<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DB;

final class TableManager {

    /** @var TableManager[] */
    private static $instances = [];
    private $tables = [];

    /** @var IDBAdapter */
    private $adapter;

    protected function __construct(IDBAdapter $adapter) {
        static::$instances[$adapter->instance_id()] = $this;
        $this->adapter = $adapter;
        $this->load();
    }

   /**
    * 
    * @return \DB\TableManager
    */
    protected function load(): TableManager {
        $rows = $this->adapter->queryAllIndex("SHOW TABLES;");
        foreach ($rows as $row) {
            if (is_array($row) && array_key_exists(0, $row)) {
                $this->tables[$row[0]] = $row[0];
            }
        }        
        return $this;
    }

    /**
     * checks is specified table exists in db
     * @param string $table_name
     * @return bool
     */
    public function exists(string $table_name): bool {
        return array_key_exists($table_name, $this->tables);
    }

    /**
     * reloads table list
     * @return \DB\TableManager
     */
    public function reload(): TableManager {
        return $this->load();        
    }

    /**
     * 
     * @param \DB\IDBAdapter $adapter
     * @return \DB\TableManager
     */
    public static function F(IDBAdapter $adapter): TableManager {
        if (array_key_exists($adapter->instance_id(), static::$instances) && (static::$instances[$adapter->instance_id()] instanceof TableManager)) {
            return static::$instances[$adapter->instance_id()];
        }
        return new static($adapter);
    }

}
