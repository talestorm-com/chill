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
class StudiosWriter {
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
        $list_raw = $writer->input->get_filtered("studios", ["NEArray", "DefaultEmptyArray"]);
        $list = [];
        foreach ($list_raw as $row) {
            if (is_array($row)) {
                try {
                    $item = \Filters\FilterManager::F()->apply_filter_array($row, $this->get_filters());
                    \Filters\FilterManager::F()->raise_array_error($item);
                    $list[] = $item;
                } catch (\Throwable $e) {
                    
                }
            }
        }
        $writer->builder->inc_counter();
        $writer->builder->push("DELETE FROM media__content__studio__list WHERE media_id={$writer->temp_var};");
        $writer->builder->inc_counter();
        if (count($list)) {
            $i = [];
            $c = 0;
            $p = [];
            foreach ($list as $item) {
                $c++;
                $i[] = "({$writer->temp_var},:P{$writer->builder->c}item_i{$c},:P{$writer->builder->c}sort_i{$c})";
                $p = array_merge($p, [
                    ":P{$writer->builder->c}item_i{$c}" => $item['id'],
                    ":P{$writer->builder->c}sort_i{$c}" => $item['sort'],
                ]);
            }
            if (count($i)) {
                $writer->builder->push(sprintf("INSERT INTO media__content__studio__list(media_id,studio_id,sort) VALUES %s ON DUPLICATE KEY UPDATE sort=VALUES(sort);", implode(",", $i)))
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
