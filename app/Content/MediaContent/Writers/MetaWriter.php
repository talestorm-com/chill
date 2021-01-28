<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers;

/**
 * Description of MetaWriter
 *
 * @author eve
 */
class MetaWriter {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(AWriter $writer) {
        $raw_data = \Filters\FilterManager::F()->apply_filter_datamap($writer->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw_data);
        $writer->builder->inc_counter();
        $language = \Language\LanguageList::F()->get_current_language();
        $writer->builder->push(sprintf("INSERT INTO media__content__meta_lang_%s(id,title,og_title,description,og_description,keywords)
            VALUES({$writer->temp_var},
                :P{$writer->builder->c}title,                
                :P{$writer->builder->c}og_title,
                :P{$writer->builder->c}description,
                :P{$writer->builder->c}og_description,
                :P{$writer->builder->c}keywords
                    )
            ON DUPLICATE KEY UPDATE title=VALUES(title),
            og_title=VALUES(og_title),
            description=VALUES(description),
            og_description=VALUES(og_description),
            keywords=VALUES(keywords);    
            ", $language))->push_params([
            ":P{$writer->builder->c}title" => $raw_data['meta_title'],
            ":P{$writer->builder->c}og_title" => $raw_data["og_title"],
            ":P{$writer->builder->c}description" => $raw_data["meta_description"],
            ":P{$writer->builder->c}og_description" => $raw_data["og_description"],
            ":P{$writer->builder->c}keywords" => $raw_data["meta_keywords"],
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'meta_title' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'og_title' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'meta_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'og_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'meta_keywords' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

}
