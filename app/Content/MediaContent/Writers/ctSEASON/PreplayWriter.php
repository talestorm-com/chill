<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctSEASON;

/**
 * Description of PreplayWriter
 *
 * @author eve
 */
class PreplayWriter {

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
        $writer->builder->push("DELETE FROM media__content__preplay WHERE content_id={$writer->temp_var};");
        if ($raw_data['preplay']) {            
            $writer->builder->push("INSERT INTO media__content__preplay(content_id,preplay_id) VALUES({$writer->temp_var},:P{$writer->builder->c}preplay);")
                    ->push_param(":P{$writer->builder->c}preplay", $raw_data['preplay'])
                    ->inc_counter();
        }
    }

    protected function get_filters() {
        return [
            'preplay' => ['IntMore0', 'DefaultNull'],
        ];
    }

}
