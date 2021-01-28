<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers;

/**
 * Description of CountriesWriter
 *
 * @author eve
 */
class CountriesWriter {
    //put your code here
    //countries

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(AWriter $writer) {
        $country_list_raw = $writer->input->get_filtered("countries", ["NEArray", "DefaultEmptyArray"]);
        $country_list = [];
        foreach ($country_list_raw as $row) {
            if (is_array($row)) {
                try {
                    $country = \Filters\FilterManager::F()->apply_filter_array($row, $this->get_filters());
                    \Filters\FilterManager::F()->raise_array_error($country);
                    $country_list[] = $country;
                } catch (\Throwable $e) {
                    
                }
            }
        }
        $writer->builder->inc_counter();
        $writer->builder->push("DELETE FROM media__content__origin WHERE id={$writer->temp_var};");
        $writer->builder->inc_counter();
        if (count($country_list)) {
            $i = [];
            $c = 0;
            $p = [];
            foreach ($country_list as $country) {
                $c++;
                $i[] = "({$writer->temp_var},:P{$writer->builder->c}country_i{$c},:P{$writer->builder->c}sort_i{$c})";
                $p = array_merge($p, [
                    ":P{$writer->builder->c}country_i{$c}" => $country['id'],
                    ":P{$writer->builder->c}sort_i{$c}" => $country['sort'],
                ]);
            }
            if (count($i)) {
                $writer->builder->push(sprintf("INSERT INTO media__content__origin(id,country_id,sort) VALUES %s ON DUPLICATE KEY UPDATE sort=VALUES(sort);", implode(",", $i)))
                        ->push_params($p)->inc_counter();
            }
        }
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0'],
            'sort' => ['Int', 'Default0'],
        ];
    }

}
