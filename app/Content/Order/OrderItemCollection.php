<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Order;

/**
 * @property OrderItem[] $items
 */
class OrderItemCollection implements \Countable, \Iterator, \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var OrderItem[] */
    protected $items;

    protected function __get__items() {
        return $this->items;
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    public function __construct(int $id = null) {
        $this->items = [];
        if ($id) {
            $this->load($id);
        }
    }

    /**
     * 
     * @param int $id
     * @param \DB\IDBAdapter $adapter
     * @return \Content\Order\OrderItemCollection
     */
    public function load(int $id, \DB\IDBAdapter $adapter = null): OrderItemCollection {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $query = "SELECT id order_id,item_guid guid,item_product_id product_id,product_name name,color_name,product_article article,sizes size,price,qty
            FROM clientorder__items WHERE id=:P";
        $rows = $adapter->queryAll($query, [":P" => $id]);
        $this->items = [];
        foreach ($rows as $row) {
            $item = OrderItem::F($row);
            if ($item && $item->valid) {
                $this->items[] = $item;
            }
        }
        return $this;
    }

    public static function F(int $id = null) {
        return new static($id);
    }

}
