<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Basket;

/**
 * пока не кешируем
 */
class StorageLoader {

    private static $instance = null;

    /** @var StorageResult[] */
    protected $loaded_items;    

    protected function __construct() {
        static::$instance = $this;
        $this->loaded_items = [];
    }

    /**
     * 
     * @return \Basket\StorageLoader
     */
    public static function F(): StorageLoader {
        return static::$instance ? static::$instance : new static();
    }

    /**
     * 
     * @param int $id
     * @return \Basket\StorageResult
     */
    public function get(int $id): StorageResult {
        $key = "P{$id}";
        if (!array_key_exists($key, $this->loaded_items)) {
            $this->loaded_items[$key] = $this->load_one($id);
        }
        return $this->loaded_items[$key];
    }

    public function get_array(array $ids) {
        $cids = [];
        $result = [];
        foreach ($ids as $id) {
            $id = intval($id);
            if ($id && $id > 0) {
                $key = "P{$id}";
                if (array_key_exists($key, $this->loaded_items)) {
                    $result[$key] = $this->loaded_items[$key];
                } else {
                    $cids[] = $id;
                }
            }
        }
        $cids = array_unique($cids);
        if (count($cids) && count($cids) > 1) {
            $result = array_merge($result, $this->load_multi($cids));
        } else if (count($cids)) {
            $result = array_merge($result, ["P{$cids[0]}"=>$this->load_one($cids[0])]);
        }
        return $result;
    }

    protected function load_one(int $id) {
        $query = "SELECT * FROM storage__contents WHERE product_id=:P";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $id]);
        $result = StorageResult::F($id, $rows);
        $this->loaded_items[$result->key] = $result;
        return $result;
    }

    protected function load_multi(array $ids) {
        $tn = "a" . md5(__METHOD__);
        $query = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;CREATE TEMPORARY TABLE `{$tn}`(id INT(11) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MEMORY;
            INSERT INTO `{$tn}`(id) VALUES(" . implode("),(", $ids) . ") ON DUPLICATE KEY UPDATE id=VALUES(id);
        ";
        \DB\DB::F()->exec($query);

        $sql = "SELECT CONCAT('P',A.id)`key`,B.* FROM `{$tn}`A JOIN storage__contents B ON(A.id=B.product_id)";
        $raw_rows = \DB\DB::F()->queryAll($sql);
        $parsed_rows = [];
        foreach ($raw_rows as $row) {
            if (!array_key_exists($row["key"], $parsed_rows)) {
                $parsed_rows[$row["key"]] = [];
            }
            $parsed_rows[$row["key"]][] = $row;
        }
        $result = [];
        foreach ($ids as $id) {
            $key = "P{$id}";
            $result_item = StorageResult::F($id, array_key_exists($key, $parsed_rows) ? $parsed_rows[$key] : []);
            $this->loaded_items[$result_item->key] = $result_item;
            $result[$result_item->key] = $result_item;
        }
        return $result;
    }

}
