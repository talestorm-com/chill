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
        $writer->builder->push("INSERT INTO media__content__collection (id,common_name,default_poster, meta_title, meta_description, additional_content) 
            VALUES({$writer->temp_var}, :P{$writer->builder->c}common_name,                
                    :P{$writer->builder->c}default_poster, :P{$writer->builder->c}meta_title, :P{$writer->builder->c}meta_description, :P{$writer->builder->c}additional_content) 
            ON DUPLICATE KEY UPDATE common_name=VALUES(common_name),default_poster=VALUES(default_poster),meta_title=VALUES(meta_title),meta_description=VALUES(meta_description),additional_content=VALUES(additional_content);    
            ")->push_params([
            ":P{$writer->builder->c}common_name" => $raw_data["common_name"],            
            ":P{$writer->builder->c}default_poster" => $raw_data["default_poster"],
            ":P{$writer->builder->c}meta_title" => $raw_data["meta_title"],
            ":P{$writer->builder->c}meta_description" => $raw_data["meta_description"],
            ":P{$writer->builder->c}additional_content" => $raw_data["additional_content"],            
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'meta_title' => ['NEString', 'DefaultEmptyString'],
            'meta_description' => ['NEString', 'DefaultEmptyString'],
            'additional_content' => ['NEString', 'DefaultEmptyString'],          
        ];
    }

}
