<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctSEASON;

/**
 * Description of LentViewWriter
 *
 * @author eve
 */
class LentViewWriter {

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
        $writer->builder->push("INSERT INTO media__lent__mode (id,mode,message) VALUES({$writer->temp_var},:P{$writer->builder->c}mode,:P{$writer->builder->c}message) ON DUPLICATE KEY UPDATE mode=VALUES(mode),message=VALUES(message);")
                ->push_params([
                    ":P{$writer->builder->c}mode" => $raw_data['lent_mode'],
                    ":P{$writer->builder->c}message" => $raw_data['lent_message']
                ])->inc_counter()
                ->push("INSERT INTO media__lent__video (id,cdn_id,cdn_url) VALUES({$writer->temp_var},:P{$writer->builder->c}cdn_id,:P{$writer->builder->c}cdn_url) ON DUPLICATE KEY UPDATE cdn_id=VALUES(cdn_id),cdn_url=VALUES(cdn_url);")
                ->push_params([
                    ":P{$writer->builder->c}cdn_id" => $raw_data['video_cdn_id'],
                    ":P{$writer->builder->c}cdn_url" => $raw_data['video_cdn_url'],
                ])->inc_counter();
        //gif - постврайтером
    }

    protected function get_filters() {
        return [
            'lent_mode' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'video_cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'video_cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'gif_cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'gif_cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'lent_message' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
