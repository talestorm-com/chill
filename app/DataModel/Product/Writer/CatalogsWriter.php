<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Writer;

class CatalogsWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \DataModel\Product\Writer\MetaWriter
     */
    public static function F(): CatalogsWriter {
        return new static();
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $catalogs = $input->get_filtered("catalogs", ['NEArray', 'DefaultEmptyArray']);
        $clean_catalogs = [];
        foreach ($catalogs as $catalog) {
            if (is_array($catalog)) {
                $raw_catalog = \Filters\FilterManager::F()->apply_filter_array($catalog, $this->get_filters());
                if (\Filters\FilterManager::F()->is_values_ok($raw_catalog)) {
                    $clean_catalogs[] = $raw_catalog;
                }
            }
        }
        $b->inc_counter();
        $b->push("DELETE FROM catalog__product__group WHERE product_id={$var};");
        $inserts = [];
        $ic = 0;
        $params = [];
        foreach ($clean_catalogs as $catalog) {
            $inserts[] = "({$var},:P{$b->c}_{$ic}_group,:P{$b->c}_{$ic}_sort)";
            $params[":P{$b->c}_{$ic}_group"] = $catalog['group'];
            $params[":P{$b->c}_{$ic}_sort"] = $catalog['sort'];
            $ic++;
        }
        if (count($inserts)) {
            $b->push(sprintf("INSERT INTO catalog__product__group(product_id,group_id,sort_in_group) VALUES %s ON DUPLICATE KEY UPDATE sort_in_group=VALUES(sort_in_group);", implode(",", $inserts)));
            $b->push_params($params);
        }
        $b->inc_counter();
    }

    protected function get_filters() {
        return [
            'group' => ['IntMore0'],
            'sort' => ['Int', 'Default0'],
        ];
    }

}
