<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctTEXT;

/**
 * Description of VideoDataWriter
 *
 * @author eve
 */
class DataWriter {
    //put your code here

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static ();
    }

    public function run(Writer $writer) {
        $raw_data = \Filters\FilterManager::F()->apply_filter_datamap($writer->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw_data);
        $writer->builder->inc_counter();
        $writer->builder->push("INSERT INTO media__content__text (id,common_name,default_poster,post) 
            VALUES({$writer->temp_var}, :P{$writer->builder->c}common_name,:P{$writer->builder->c}default_poster,:P{$writer->builder->c}post) 
            ON DUPLICATE KEY UPDATE common_name=VALUES(common_name),default_poster=VALUES(default_poster),post=VALUES(post);    
            ")->push_params([
            ":P{$writer->builder->c}common_name" => $raw_data["common_name"],
            ":P{$writer->builder->c}post" => $raw_data["post"] ? $raw_data['post']->format('Y-m-d H:i:s') : null,
            ":P{$writer->builder->c}default_poster" => $raw_data["default_poster"],
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'post' => ['DateMatch', 'DefaultNull',],
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
