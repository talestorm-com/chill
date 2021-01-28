<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctCOLLECTION;

/**
 * Description of LentViewWriter
 * media__lent__mode_page
 *
 * @author eve
 */
class PageViewWriter {

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
        $writer->builder->push("INSERT INTO media__lent__mode_page (id,lent_mode2,lent_message2,gif_cdn_id2,gif_cdn_url2,video_cdn_id2,video_cdn_url2,lent_image_name2) 
            VALUES({$writer->temp_var},:P{$writer->builder->c}mode,:P{$writer->builder->c}message,
                :P{$writer->builder->c}gif_cdn_id,:P{$writer->builder->c}gif_cdn_url,
                    :P{$writer->builder->c}video_cdn_id,:P{$writer->builder->c}video_cdn_url,NULL                
                ) ON DUPLICATE KEY UPDATE lent_mode2=VALUES(lent_mode2),lent_message2=VALUES(lent_message2),
                gif_cdn_id2=VALUES(gif_cdn_id2),
                gif_cdn_url2=VALUES(gif_cdn_url2),
                video_cdn_id2=VALUES(video_cdn_id2),
                video_cdn_url2=VALUES(video_cdn_url2);")//imagename не обновляем - его если надо перезапишет врайтер
                ->push_params([
                    ":P{$writer->builder->c}mode" => $raw_data['lent_mode2'],
                    ":P{$writer->builder->c}message" => $raw_data['lent_message2'],
                    ":P{$writer->builder->c}gif_cdn_id" => $raw_data['gif_cdn_id2'],
                    ":P{$writer->builder->c}gif_cdn_url" => $raw_data['gif_cdn_url2'],
                    ":P{$writer->builder->c}video_cdn_id" => $raw_data['video_cdn_id2'],
                    ":P{$writer->builder->c}video_cdn_url" => $raw_data['video_cdn_url2'],
                ])->inc_counter();
        //gif - постврайтером
    }

    protected function get_filters() {
        return [
            'lent_mode2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'video_cdn_id2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'video_cdn_url2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'gif_cdn_id2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'gif_cdn_url2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'lent_message2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
