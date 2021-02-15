<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile;

/**
 * 
 */
class CatalogCollection implements \Iterator, \Countable, \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var CatalogEntry[] */
    protected $items;

    public function __construct(int $id = null) {
        $this->items = [];
        $id ? $this->load($id) : 0;
    }

    /**
     * 
     * @param int $id
     * @param \DB\IDBAdapter $adapter
     * @return $this
     */
    public function load(int $id, \DB\IDBAdapter $adapter = null) {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $query = "SELECT A.c_id id,A.sort,A.override,A.image_id,B.name,B.alias,B.default_image,B.visible
            FROM catalog__tile__catalog A JOIN catalog__group B ON(A.c_id=B.id)
            WHERE A.t_id=:P    
            ORDER BY A.sort,A.c_id
            ";
        $rows = $adapter->queryAll($query, [":P" => $id]);
        $this->items = [];
        $tree = \CatalogTree\CatalogTreeSinglet::F()->tree;
        foreach ($rows as $row) {
            $item = CatalogEntry::F($row);
            if ($item->valid) {
                $this->items[] = $item->merge_node_data($tree);
            }
        }
        return $this;
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    /**
     * 
     * @param int $id
     * @return \Content\CatalogTile\CatalogCollection
     */
    public static function F(int $id = null): CatalogCollection {
        return new static($id);
    }

    /**
     * 
     * @param int $index
     * @return \Content\CatalogTile\CatalogEntry
     */
    public function get_index(int $index = 0) {
        return array_key_exists($index, $this->items) ? $this->items[$index] : null;
    }

}
