<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\Trailer;

/**
 * Description of CommonDataWriter
 *
 * @author eve
 */
class TrailerDataWriter {

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
        $writer->builder->push("INSERT INTO media__content__trailer(id,content_id,vertical,sort,default_image,target_url) VALUES(
                {$writer->temp_var},
                :P{$writer->builder->c}content_id,:P{$writer->builder->c}vertical,:P{$writer->builder->c}sort,
                :P{$writer->builder->c}default_image,:P{$writer->builder->c}target_url
                ) ON DUPLICATE KEY UPDATE vertical=VALUES(vertical),sort=VALUES(sort),default_image=VALUES(default_image),target_url=VALUES(target_url) ;")
        ;
        $writer->builder->push_params([
            ":P{$writer->builder->c}content_id" => $raw_data["content_id"],
            ":P{$writer->builder->c}vertical" => $raw_data["vertical"],
            ":P{$writer->builder->c}sort" => $raw_data["sort"],
            ":P{$writer->builder->c}default_image" => $raw_data["default_image"],
            ":P{$writer->builder->c}target_url" => $raw_data["target_url"],
        ]);
        $writer->builder->inc_counter();
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'content_id' => ['IntMore0'],
            'sort' => ['Int', 'Default0'],
            //'enabled' => ['Boolean', 'DefaultFalse', 'SQLBool'],
            'vertical' => ['Boolean', 'DefaultFalse', 'SQLBool'],
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'target_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
