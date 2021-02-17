<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctCOLLECTION;

/**
 * Description of VideoDataWriter
 *
 * @author eve
 */
class ItemsWriter {
    //put your code here

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static ();
    }

    public function run(Writer $writer) {
        $writer->builder->inc_counter()->push("DELETE FROM media__content__collection__items WHERE collection_id={$writer->temp_var};")->inc_counter();

        $raw_data = \Filters\FilterManager::F()->apply_filter_datamap($writer->input, ['items' => ['NEArray', 'DefaultEmptyArray']]);
        \Filters\FilterManager::F()->raise_array_error($raw_data);
        $c = 0;
        $ins = [];
        $par = [];
        foreach ($raw_data['items'] as $raw_item) {
            try {
                $clean_raw_item = \Filters\FilterManager::F()->apply_filter_array($raw_item, $this->get_filters());
                \Filters\FilterManager::F()->raise_array_error($clean_raw_item);
                $c++;
                $ins[] = "({$writer->temp_var},:P{$writer->builder->c}_i{$c}_content_id,:P{$writer->builder->c}_i{$c}_sort)";
                $par = array_merge($par, [
                    ":P{$writer->builder->c}_i{$c}_content_id" => $clean_raw_item["content_id"],
                    ":P{$writer->builder->c}_i{$c}_sort" => $c,
                ]);
                $c++;
            } catch (\Throwable $e) {
                
            }
        }
        if (count($ins)) {
            $writer->builder->inc_counter();
            $writer->builder->push_params($par);
            $writer->builder->push(sprintf("INSERT INTO media__content__collection__items (collection_id,content_id,sort)
                VALUES %s ON DUPLICATE KEY UPDATE sort=VALUES(sort);
                ", implode(",", $ins)));
            $writer->builder->inc_counter();
        }
    }

    protected function get_filters() {
        return [
            'content_id' => ['IntMore0',],
        ];
    }

}
