<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Model;

/**
 * @property ProductCatalog[] $items
 * @property int $product_id
 * @property bool $empty
 */
class ProductCatalogCollection implements \Iterator, \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator,
        \common_accessors\TDefaultMarshaller;

    /** @var ProductCatalog[] */
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
    
    protected function __get__empty(){
        return count($this->items)?false:true;
    }

    public function __construct(int $id, bool $skip_load = false) {
        $this->product_id = $id;
        $this->product_key="P{$this->product_id}";
        $this->items = [];
        if (!$skip_load) {
            $this->load();
        }
    }

    protected function load() {
        $query = "SELECT 
            CONCAT('P',product_id) `product_key`, group_id id,sort_in_group sort,C.name,C.alias,C.guid
            FROM catalog__product__group A JOIN catalog__group C ON(C.id=A.group_id)
            WHERE A.product_id=:Pid;
            ";
        $rows = \DB\DB::F()->queryAll($query, [":Pid" => $this->product_id]);
        
        $this->import_raw($rows);
    }

    public function import_raw(array $rows) {
        $catalog_voc = \CatalogTree\CatalogTreeSinglet::F()->tree;
        foreach ($rows as $row) {
            if ($row['product_key'] === $this->product_key) {
                $catalog = ProductCatalog::F($row);
                if ($catalog && $catalog->valid) {
                    $catalog->recover_path($catalog_voc);
                    $this->items[] = $catalog;
                }
            }
        }
    }

    public static function F(int $id, bool $skip_load = false): ProductCatalogCollection {
        return new static($id,$skip_load);
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    public static function load_join(string $table) {
        $query = "SELECT 
            CONCAT('P',product_id) `product_key`, group_id id,sort_in_group sort,C.name,C.alias,C.guid
            FROM 
            `{$table}` SUPPx JOIN
            catalog__product__group A ON(A.product_id=SUPPx.id)
            JOIN catalog__group C ON(C.id=A.group_id)            
            ";
        return \DB\DB::F()->queryAll($query);
    }

}
