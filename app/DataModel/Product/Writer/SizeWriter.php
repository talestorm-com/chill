<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Writer;

class SizeWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \DataModel\Product\Writer\SizeWriter
     */
    public static function F(): SizeWriter {
        return new static();
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $sizes = $input->get_filtered("sizes", ['NEArray', 'DefaultEmptyArray']);
        $clean_sizes = [];
        foreach ($sizes as $size) {
            if (is_array($size)) {
                $raw_size = \Filters\FilterManager::F()->apply_filter_array($size, $this->get_filters());
                if (\Filters\FilterManager::F()->is_values_ok($raw_size)) {
                    $clean_sizes[] = $raw_size;
                }
            }
        }
        $b->inc_counter();
        $b->push("DELETE FROM catalog__product__size WHERE product_id={$var};");
        $inserts = [];
        $ic = 0;
        $params = [];
        foreach ($clean_sizes as $size) {
            $inserts[] = "({$var},:P{$b->c}_{$ic}_size,:P{$b->c}_{$ic}_enabled)";
            $params[":P{$b->c}_{$ic}_size"] = $size['id'];
            $params[":P{$b->c}_{$ic}_enabled"] = $size['enabled'];
            $ic++;
        }
        if (count($inserts)) {
            $b->push(sprintf("INSERT INTO catalog__product__size(product_id,size_id,enabled) VALUES %s ON DUPLICATE KEY UPDATE enabled=VALUES(enabled);", implode(",", $inserts)));
            $b->push_params($params);
        }
        $b->inc_counter();
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0'],
            'enabled' => ['Boolean', 'DefaultTrue', 'SQLBool'],
        ];
    }

}
