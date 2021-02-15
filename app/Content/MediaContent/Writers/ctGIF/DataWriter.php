<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctGIF;

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
        $writer->builder->push("INSERT INTO media__content__gif (id,common_name,default_poster,cdn_url,cdn_id,target) 
            VALUES({$writer->temp_var}, :P{$writer->builder->c}common_name,                
                    :P{$writer->builder->c}default_poster,null,:P{$writer->builder->c}cdn_id,:P{$writer->builder->c}target) 
            ON DUPLICATE KEY UPDATE common_name=VALUES(common_name),default_poster=VALUES(default_poster),cdn_id=VALUES(cdn_id),target=VALUES(target);    
            ")->push_params([
            ":P{$writer->builder->c}common_name" => $raw_data["common_name"],
            ":P{$writer->builder->c}default_poster" => $raw_data["default_poster"],
            ":P{$writer->builder->c}cdn_id" => $raw_data["cdn_id"],
            ":P{$writer->builder->c}target" => $raw_data["target"],
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'target' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
