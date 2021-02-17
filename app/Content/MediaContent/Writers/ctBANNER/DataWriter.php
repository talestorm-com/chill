<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctBANNER;

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
        $writer->builder->push("INSERT INTO media__content__banner (id,name,background,text_color,default_poster) 
            VALUES({$writer->temp_var}, :P{$writer->builder->c}name,:P{$writer->builder->c}background,
                :P{$writer->builder->c}text_color,:P{$writer->builder->c}default_poster) 
            ON DUPLICATE KEY UPDATE name=VALUES(name),background=VALUES(background),text_color=VALUES(text_color),default_poster=VALUES(default_poster);    
            ")->push_params([
            ":P{$writer->builder->c}name" => $raw_data["name"],
            ":P{$writer->builder->c}background" => $raw_data['background'],
            ":P{$writer->builder->c}text_color" => $raw_data["text_color"],
            ":P{$writer->builder->c}default_poster" => $raw_data["default_poster"],
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'name' => ['Strip', 'Trim', 'NEString'],
            'background' => ['HTMLColor',],
            'text_color' => ['HTMLColor',],
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
