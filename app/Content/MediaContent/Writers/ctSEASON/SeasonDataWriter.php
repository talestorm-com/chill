<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctSEASON;

/**
 * Description of VideoDataWriter
 *
 * @author eve
 */
class SeasonDataWriter {
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
        $writer->builder->push("INSERT INTO media__content__season (id,common_name,default_poster,eng_name,released,origin_language,copyright_holder) 
            VALUES({$writer->temp_var}, :P{$writer->builder->c}common_name,:P{$writer->builder->c}default_poster,:P{$writer->builder->c}eng_name,
               :P{$writer->builder->c}released,:P{$writer->builder->c}origin_language,:P{$writer->builder->c}copyright_holder
                ) 
            ON DUPLICATE KEY UPDATE common_name=VALUES(common_name),default_poster=VALUES(default_poster),eng_name=VALUES(eng_name),
            released=VALUES(released),origin_language=VALUES(origin_language),copyright_holder=VALUES(copyright_holder);
            ")->push_params([
            ":P{$writer->builder->c}common_name" => $raw_data["common_name"],
            ":P{$writer->builder->c}default_poster" => $raw_data["default_poster"],
            ":P{$writer->builder->c}eng_name" => $raw_data["eng_name"],
            ":P{$writer->builder->c}copyright_holder" => $raw_data["copyright_holder"],
            ":P{$writer->builder->c}origin_language" => $raw_data["origin_language"],
            ":P{$writer->builder->c}released" => $raw_data["released"] ? $raw_data['released']->format('Y-m-d H:i:s') : null,
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'eng_name' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'origin_language' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'released' => ['DateMatch', 'DefaultNull'],
            'copyright_holder' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
