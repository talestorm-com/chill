<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Basket;

/**
 * @property int $id
 * @property string $key
 * @property StorageResultItem[] $items
 */
class StorageResult implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var int */
    protected $id;

    /** @var string */
    protected $key;

    /** @var StorageResultItem[] */
    protected $items;

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__key() {
        return $this->key;
    }

    /** @return StorageResultItem[] */
    protected function __get__items() {
        return $this->items;
    }

    protected function __construct(int $id, array $rows) {
        $this->id = $id;
        $this->key = "P{$id}";
        $this->items = [];
        foreach ($rows as $row) {
            $this->items[] = StorageResultItem::F($row);
        }
    }

    /**
     * 
     * @param int $id
     * @param array $rows
     * @return \Basket\StorageResult
     */
    public static function F(int $id, array $rows): StorageResult {
        return new static($id, $rows);
    }

}
