<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Model;

/**
 * @property ProductSize[] $items
 * @property int $product_id
 * @property bool $empty
 */
class ProductSizeCollection implements \Iterator, \common_accessors\IMarshall, \Countable {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator,
        \common_accessors\TDefaultMarshaller;

    /** @var ProductSize[] */
    protected $items;

    /** @var int */
    protected $product_id;
    protected $product_key;

    protected function __get__items() {
        return $this->items;
    }

    protected function __get__product_id() {
        return $this->product_id;
    }

    protected function __get__empty() {
        return count($this->items) ? false : true;
    }

    public function __construct(int $id, bool $skip_load = false) {
        $this->product_id = $id;
        $this->product_key = "P{$this->product_id}";
        $this->items = [];
        if (!$skip_load) {
            $this->load();
        }
    }

    protected function load() {
        $query = "SELECT CONCAT('P',A.product_id) `product_key`, A.product_id,B.id,B.guid, B.size `value`,A.`enabled`
            FROM  catalog__product__size A JOIN catalog__size__def B ON(A.size_id=B.id)
            WHERE A.product_id=:Pid
            ORDER BY B.size;
            ";
        $rows = \DB\DB::F()->queryAll($query, [":Pid" => $this->product_id]);

        $this->import_raw($rows);
    }

    public function import_raw(array $rows) {
        foreach ($rows as $row) {
            if ($row['product_key'] === $this->product_key) {
                $size = ProductSize::F($row);
                if ($size && $size->valid) {
                    $this->items[] = $size;
                }
            }
        }
    }

    public static function F(int $id, bool $skip_load = false): ProductSizeCollection {
        return new static($id, $skip_load);
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    /**
     * 
     * @param string $table
     * @return array
     */
    public static function load_join(string $table) {
        $query = "SELECT CONCAT('P',A.product_id) `product_key`, A.product_id,B.id,B.guid, B.size `value`,A.`enabled`
            FROM `{$table}` SUPPx
                JOIN catalog__product__size A ON(A.product_id=SUPPx.id)
                JOIN catalog__size__def B ON(A.size_id=B.id)
            ORDER BY B.size;";
        return \DB\DB::F()->queryAll($query);
    }
    
    
    /**
     * 
     * @param int $id
     * @param type $default
     * @return ProductSize
     */
    public function get_by_id(int $id,$default = null){
        //
        foreach($this->items as $item){
            if($id===$item->id){
                return $item;
            }
        }
        return $default;
    }

}
