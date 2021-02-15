<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctCOLLECTION;

/**
 * Description of StringsWriter
 *
 * @author eve
 */
class StringsWriter {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(Writer $writer) {
        $raw_data = \Filters\FilterManager::F()->apply_filter_datamap($writer->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw_data);
        $writer->builder->inc_counter();
        $language = \Language\LanguageList::F()->get_current_language();
        $writer->builder->push(sprintf("INSERT INTO media__content__collection_strings_lang_%s(id,name,html_mode,intro)
            VALUES(
                {$writer->temp_var},:P{$writer->builder->c}name,                
                :P{$writer->builder->c}html_mode,:P{$writer->builder->c}intro
                  )
            ON DUPLICATE KEY UPDATE name=VALUES(name),
            html_mode=VALUES(html_mode),intro=VALUES(intro);    
            ", $language))->push_params([
            ":P{$writer->builder->c}name" => $raw_data['name'],
            ":P{$writer->builder->c}html_mode" => $raw_data["html_mode"],
            ":P{$writer->builder->c}intro" => $raw_data["intro"],
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'name' => ['Strip', 'Trim', 'NEString'],
            'html_mode' => ['IntMore0', 'Default0'],
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

}
