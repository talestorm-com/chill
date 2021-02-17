<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctVIDEO;

/**
 * Description of VideoDataWriter
 *
 * @author eve
 */
class VideoDataWriter {
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
        $writer->builder->push("INSERT INTO media__content__video (id,common_name,vertical,cdn_id,year,default_poster,default_frame) 
            VALUES({$writer->temp_var}, :P{$writer->builder->c}common_name,:P{$writer->builder->c}vertical,
                :P{$writer->builder->c}cdn_id,:P{$writer->builder->c}year,
                    :P{$writer->builder->c}default_poster,:P{$writer->builder->c}default_frame
                    ) 
            ON DUPLICATE KEY UPDATE common_name=VALUES(common_name),vertical=VALUES(vertical),cdn_id=VALUES(cdn_id),year=VALUES(year),default_poster=VALUES(default_poster),
            default_frame=VALUES(default_frame);    
            ")->push_params([
            ":P{$writer->builder->c}common_name" => $raw_data["common_name"],
            ":P{$writer->builder->c}vertical" => $raw_data['vertical'],
            ":P{$writer->builder->c}cdn_id" => $raw_data["cdn_id"],
            ":P{$writer->builder->c}year" => $raw_data["year"],
            ":P{$writer->builder->c}default_poster" => $raw_data["default_poster"],
            ":P{$writer->builder->c}default_frame" => $raw_data["default_frame"],
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'vertical' => ['Boolean', 'DefaultFalse', 'SQLBool'],
            'cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'year' => ['IntMore0', 'DefaultNull'],
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'default_frame' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
