<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Writer;

class PriceWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \DataModel\Product\Writer\PriceWriter
     */
    public static function F(): PriceWriter {
        return new static();
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $b->inc_counter();
        $data = \Filters\FilterManager::F()->apply_filter_datamap($input, $this->get_filters());
        $query = "INSERT INTO catalog__product__price (id,retail,gross,retail_old,gross_old,discount_retail,discount_gross)
            VALUES({$var},:P{$b->c}r,:P{$b->c}g,:P{$b->c}or,:P{$b->c}og,:P{$b->c}dr,:P{$b->c}dg)
            ON DUPLICATE KEY UPDATE 
            retail = COALESCE(VALUES(retail),retail),
            gross = COALESCE(VALUES(gross),gross),
            retail_old = VALUES(retail_old),
            gross_old = VALUES(gross_old),
            discount_retail = VALUES(discount_retail),
            discount_gross = VALUES(discount_gross);
            ";
        $b->push($query)->push_params([
            ":P{$b->c}r" => $data['retail'],
            ":P{$b->c}g" => $data['gross'],
            ":P{$b->c}or" => $data['retail_old'],
            ":P{$b->c}og" => $data['gross_old'],
            ":P{$b->c}dr" => $data['discount_retail'],
            ":P{$b->c}dg" => $data['discount_gross'],
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'retail' => ['Float', 'DefaultNull'],
            'gross' => ['Float', 'DefaultNull'],
            'retail_old' => ['Float', 'DefaultNull'],
            'gross_old' => ['Float', 'DefaultNull'],
            'discount_retail' => ['Float', 'DefaultNull'],
            'discount_gross' => ['Float', 'DefaultNull'],
        ];
    }

}
