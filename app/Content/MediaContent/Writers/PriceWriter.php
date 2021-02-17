<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers;

/**
 * Description of PriceWriter
 *
 * @author eve
 */
class PriceWriter {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(AWriter $writer) {
        $raw_data = \Filters\FilterManager::F()->apply_filter_datamap($writer->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw_data);
        $writer->builder->inc_counter();
        $language = \Language\LanguageList::F()->get_current_language();
        $writer->builder->push("INSERT INTO media__content__price(id,price)
            VALUES({$writer->temp_var},
                :P{$writer->builder->c}price
                )
            ON DUPLICATE KEY UPDATE price=VALUES(price)
            ;    
            ")->push_params([
            ":P{$writer->builder->c}price" => $raw_data['price'],
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'price' => ['Float', 'DefaultNull'],
        ];
    }

}
