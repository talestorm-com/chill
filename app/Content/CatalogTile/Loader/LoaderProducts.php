<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile\Loader;

class LoaderProducts extends AbstractLoader {

    CONST SORT_OVERRIDE = [
        "DISCOUNT0" => \DataModel\Product\Model\IProductSortMode::SM_DISCOUNT_RETAIL,
        "DISCOUNT_REV0" => \DataModel\Product\Model\IProductSortMode::SM_DISCOUNT_RETAIL_REV,
        "DISCOUNT1" => \DataModel\Product\Model\IProductSortMode::SM_DISCOUNT_GROSS,
        "DISCOUNT_REV1" => \DataModel\Product\Model\IProductSortMode::SM_DISCOUNT_GROSS_REV,
        "PRICE0" => \DataModel\Product\Model\IProductSortMode::SM_PRICE_RETAIL,
        "PRICE_REV0" => \DataModel\Product\Model\IProductSortMode::SM_PRICE_RETAIL_REV,
        "PRICE1" => \DataModel\Product\Model\IProductSortMode::SM_PRICE_GROSS,
        "PRICE_REV1" => \DataModel\Product\Model\IProductSortMode::SM_PRICE_GROSS_REV,
    ];

    protected static function get_loader_description(): string {

        return "Товары из выбранных каталогов и дочерних";
    }

    protected static function get_loader_name(): string {
        return "Products";
    }

    protected function load_int(array $ids, \Content\CatalogTile\CatalogTile $tile, int $dealer) {
        $tn = "a" . md5(__METHOD__);
        $query = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
            DROP TEMPORARY TABLE IF EXISTS `{$tn}p`;
            CREATE TEMPORARY TABLE `{$tn}` (id INT(11) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MEMORY;
            CREATE TEMPORARY TABLE `{$tn}p` (id INT(11) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MEMORY;    
            INSERT INTO `{$tn}`(id) VALUES (" . implode("),(", $ids) . ") ON DUPLICATE KEY UPDATE id=VALUES(id);
            INSERT INTO `{$tn}p` (id)    
                SELECT P.id FROM `{$tn}` A JOIN catalog__product__group L ON(L.group_id=A.id)
                    JOIN catalog__product P ON(P.id=L.product_id)
                WHERE 1=1 " . ($tile->ignore_product_visibility ? "" : " AND P.enabled=1 ") . " 
            ON DUPLICATE KEY UPDATE `{$tn}p`.id=VALUES(id);                                
        ";
        $db = \DB\DB::F();
        $db->exec($query);
        \DB\errors\MySQLWarn::F($db);
        $limit = $tile->properties->get_filtered("limit", ["IntMore0", "DefaultNull"]);
        $limit ? 0 : $limit = 12;
        $limit > 50 ? $limit = 12 : 0;
        $sort_mode = $tile->properties->get_filtered("sort_mode", ['Trim', 'NEString', 'DefaultNull']);
        if ($sort_mode) {
            $tmp_sm = "{$sort_mode}{$dealer}";
            if (array_key_exists($tmp_sm, static::SORT_OVERRIDE)) {
                $sort_mode = static::SORT_OVERRIDE[$tmp_sm];
            }
        }
        $products = \DataModel\Product\Model\ProductModel::load_join("{$tn}p", $limit, $sort_mode);
        if (count($products) && count($products) < $limit) {
            if ($tile->properties->get_filtered('fill_to_limit', ['Boolean', 'DefaultFalse'])) {
                $co = 0;
                while (count($products) < $limit) {
                    $products[] = $products[$co];
                    $co++;
                }
            }
        }
        return $products;
    }

    public function load(\Content\CatalogTile\CatalogTile $tile, \Content\CatalogTile\CatalogTileFull $full_tile = null): array {
        $catalogs = [];
        if (count($tile->catalogs)) {
            $tree = \CatalogTree\CatalogTreeSinglet::F()->tree;

            foreach ($tile->catalogs as $catalog) {/* @var $catalog \Content\CatalogTile\CatalogEntry */
                $catalogs = array_merge($catalogs, $tree->enum_childs_ids_of_id($catalog->id, true));
            }
            if (!$tile->ignore_catalog_visibility) {
                $t = $catalog;
                $catalogs = [];
                foreach ($t as $catalog_id) {
                    $catalog = $tree->get_item_by_id($catalog_id); /* @var $catalog \CatalogTree\CatalogTreeItem */
                    if ($catalog && $catalog->visible) {
                        $catalogs[] = $catalog_id;
                    }
                }
            }
            if (count($catalogs)) {
                return $this->load_int(array_unique($catalogs), $tile, $full_tile->dealer ? 1 : 0);
            }
        }
        return [];
    }

}
