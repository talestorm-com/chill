<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Model;

/**
 * @property bool $empty
 */
class ProductColorCollection implements \Iterator, \common_accessors\IMarshall, \Countable {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator,
        \common_accessors\TDefaultMarshaller;

    protected $product_id;
    protected $product_key;

    /** @var ProductColor[] */
    protected $items;

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

    /**
     * 
     * @param string $uid
     * @param type $default
     * @return ProductColor
     */
    public function get_by_uid(string $uid, $default = null) {
        foreach ($this->items as $item) {
            if ($item->guid === $uid) {
                return $item;
            }
        }
        return $default;
    }
    /**
     * 
     * @param string $uid
     * @param type $default
     * @return ProductColor
     */
    public function get_by_guid(string $uid, $default = null) {
        foreach ($this->items as $item) {
            if ($item->guid === $uid) {
                return $item;
            }
        }
        return $default;
    }

    protected function load(int $id) {
        $query = "SELECT CONCAT('P',A.product_id) `product_key`, A.*,S.name FROM catalog__product__color A JOIN catalog__product__color__strings S
            ON (S.guid=A.guid)
            WHERE product_id=:P ORDER BY A.sort,A.guid";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $id]);
        $this->import_colors_raw($rows);
    }

    public function import_colors_raw(array $colors) {
        $IFIM = \ImageFly\ImageInfoManager::F();
        foreach ($colors as $row) {
            if ($row['product_key'] == $this->product_key) {
                $color = ProductColor::F($row);
                if ($color && $color->valid) {
                    $this->items[] = $color;
                    $color->check_image_exists($IFIM);
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
     * @return \DataModel\Product\Model\ProductColorCollection
     */
    public static function F(int $id, bool $skip_load = false): ProductColorCollection {
        return new static($id, $skip_load);
    }

    /**
     * load all colors as raw array for products in table {$table_name}.id
     * @param string $table_name     
     * @return array
     */
    public static function load_join(string $table_name) {
        $query = "SELECT CONCAT('P',A.product_id) `product_key`, A.*,S.name 
             FROM `{$table_name}` SUPPx JOIN catalog__product__color A ON(SUPPx.id=A.product_id)
                 JOIN catalog__product__color__strings S
            ON (S.guid=A.guid)
            ORDER BY A.sort,A.guid";
        return \DB\DB::F()->queryAll($query);
    }

}
