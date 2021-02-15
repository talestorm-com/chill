<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Model;

class ProductCrossCollection implements \Countable, \Iterator, \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator,
        \common_accessors\TDefaultMarshaller;

    /** @var ProductCross[] */
    protected $items;
    protected $product_id;
    protected $product_key;

    protected function __get__empty() {
        return count($this->items) ? false : true;
    }

    protected function __construct(int $id, bool $skip_load = false) {
        $this->product_id = $id;
        $this->product_key = "P{$this->product_id}";
        $this->items = [];
        if (!$skip_load) {            
            $this->load($id);
        }
    }

    protected function load(int $id) {
        $query = "SELECT CONCAT('P',A.product_1) `product_key`,
            A.product_2 id,P.article,P.alias,P.sort,P.default_image,S.name,P.guid,
            C.retail,C.gross,C.retail_old,C.gross_old,C.discount_retail,C.discount_gross,P.enabled
            FROM catalog__product__product__link A JOIN catalog__product P ON(P.id=A.product_2)
            JOIN catalog__product__strings S ON(S.id=P.id)
            LEFT JOIN catalog__product__price C ON(C.id=P.id)
            WHERE A.product_1=:P ORDER BY P.sort,A.product_2;
            ";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $id]);
        $this->import_raw($rows);           
    }

    public function import_raw(array $crosses) {
        foreach ($crosses as $row) {
            if ($row['product_key'] == $this->product_key) {
                $cross = ProductCross::F($row);
                if ($cross && $cross->valid) {
                    $this->items[] = $cross;
                }
            }
        }
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    /**
     * 
     * @param int $id
     * @return \DataModel\Product\Model\ProductCrossCollection
     */
    public static function F(int $id, bool $skip_load = false): ProductCrossCollection {
        return new static($id, $skip_load);
    }

    /**
     * load all cross as raw array for products in table {$table_name}.id
     * @param string $table_name     
     * @return array
     */
    public static function load_join(string $table_name) {
        $query = "
            SELECT CONCAT('P',A.product_1) `product_key`
            ,A.product_2 id
            ,P.article
            ,P.alias
            ,P.sort
            ,P.default_image
            ,S.name
            ,P.guid
            ,C.retail
            ,C.gross
            ,C.retail_old
            ,C.gross_old
            ,C.discount_retail
            ,C.discount_gross 
            ,P.enabled
            FROM `{$table_name}` SUP JOIN catalog__product__product__link A ON(A.product_1=SUP.id)
            JOIN catalog__product P ON(P.id=A.product_2)
            JOIN catalog__product__strings S ON(S.id=P.id)
            LEFT JOIN catalog__product__price C ON(C.id=P.id)            
            ";         
        return \DB\DB::F()->queryAll($query);
    }

}
