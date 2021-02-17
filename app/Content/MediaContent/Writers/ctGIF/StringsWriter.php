<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctGIF;

/**
 * Description of StringsWriter
 *
 * @author eve
 */
class StringsWriter {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(Writer $writer) {
        $raw_data = \Filters\FilterManager::F()->apply_filter_datamap($writer->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw_data);
        $writer->builder->inc_counter()->push("DELETE  FROM media__content__gif__strings WHERE id={$writer->temp_var};")->inc_counter();
        $i = [];
        $c = 0;
        $p = [];
        foreach ($raw_data['strings'] as $row) {
            try {
                $item = \Filters\FilterManager::F()->apply_filter_array(is_array($row) ? $row : [], $this->get_item_filters());
                \Filters\FilterManager::F()->raise_array_error($item);
                $c++;
                $i[] = "({$writer->temp_var},:P{$writer->builder->c}_i{$c}_language_id,:P{$writer->builder->c}_i{$c}_name)";
                $p = array_merge($p, [
                    ":P{$writer->builder->c}_i{$c}_language_id" => $item["language_id"],
                    ":P{$writer->builder->c}_i{$c}_name" => $item["text"],
                ]);
            } catch (\Throwable $e) {
                
            }
        }
        if (count($i)) {
            $writer->builder->push(sprintf("INSERT INTO media__content__gif__strings (id,language_id,name) VALUES %s ON DUPLICATE KEY UPDATE name=VALUES(name);", implode(",", $i)))
                    ->push_params($p)->inc_counter();
        }
    }

    protected function get_filters() {
        return [
            'strings' => ['NEArray', 'DefaultEmptyArray'],
        ];
    }

    protected function get_item_filters() {
        return [
            'language_id' => ['Strip', 'Trim', 'NEString'],
            'text' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
