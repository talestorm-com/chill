<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers;

/**
 * Description of PersonalWriter
 *
 * @author eve
 */
class PersonalWriter {

    public static function F() {
        return new static();
    }

//personal

    public function run(AWriter $writer) {
        $list_raw = $writer->input->get_filtered("personal", ["NEArray", "DefaultEmptyArray"]);
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
        $writer->builder->push("DELETE FROM media__content__personal WHERE content_id={$writer->temp_var};");
        $writer->builder->inc_counter();
        if (count($list)) {
            $i = [];
            $c = 0;
            $p = [];
            foreach ($list as $item) {
                $c++;
                $i[] = "({$writer->temp_var},:P{$writer->builder->c}item_i{$c},:P{$writer->builder->c}value_i{$c},:P{$writer->builder->c}sort_i{$c})";
                $p = array_merge($p, [
                    ":P{$writer->builder->c}item_i{$c}" => $item['person_id'],
                    ":P{$writer->builder->c}sort_i{$c}" => $item['sort'],
                    ":P{$writer->builder->c}value_i{$c}" => $item['value'],
                ]);
            }
            if (count($i)) {
                $writer->builder->push(sprintf("INSERT INTO media__content__personal(content_id,person_id,`value`,sort) VALUES %s ON DUPLICATE KEY UPDATE sort=VALUES(sort);", implode(",", $i)))
                        ->push_params($p)->inc_counter();
            }
        }
    }

    protected function get_filters() {
        return [
            'person_id' => ['IntMore0'],
            'sort' => ['Int', 'Default0'],
            'value' => ['Strip', 'Trim', 'NEString'],
        ];
    }

}
