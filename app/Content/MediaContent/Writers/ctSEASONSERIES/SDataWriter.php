<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctSEASONSERIES;

/**
 * Description of VideoDataWriter
 *
 * @author eve
 */
class SDataWriter {
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
        $writer->builder->push("INSERT INTO media__content__season__series (id,seasonseason_id,num,default_poster,vertical,common_name,default_preview) 
            VALUES({$writer->temp_var}, :P{$writer->builder->c}seasonseason_id,:P{$writer->builder->c}num,:P{$writer->builder->c}default_poster,:P{$writer->builder->c}vertical,
                :P{$writer->builder->c}common_name,:P{$writer->builder->c}default_preview) 
            ON DUPLICATE KEY UPDATE num=VALUES(num),default_poster=VALUES(default_poster), common_name=VALUES(common_name),vertical=VALUES(vertical),default_preview=VALUES(default_preview);    
            ")->push_params([
            ":P{$writer->builder->c}num" => $raw_data["num"],
            ":P{$writer->builder->c}vertical" => $raw_data["vertical"],
            ":P{$writer->builder->c}common_name" => $raw_data["common_name"],
            ":P{$writer->builder->c}seasonseason_id" => $raw_data["seasonseason_id"],
            ":P{$writer->builder->c}default_poster" => $raw_data["default_poster"],
            ":P{$writer->builder->c}default_preview" => $raw_data["default_preview"],
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'seasonseason_id' => ['IntMore0',],
            'num' => ['IntMore0'],
            'vertical' => ['Boolean', 'DefaultFalse', 'SQLBool'],
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'default_preview' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
