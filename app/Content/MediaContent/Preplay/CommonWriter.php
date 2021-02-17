<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Preplay;

/**
 * Description of CommonWriter
 *
 * @author eve
 */
class CommonWriter {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static ();
    }

    public function run(Writer $writer) {
        $data = \Filters\FilterManager::F()->apply_filter_datamap($writer->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $writer->builder->inc_counter();
        if ($data['id']) {
            $writer->builder->push("SET {$writer->temp_var} = :P{$writer->builder->c}id;")
                    ->push("UPDATE media__preplay__video SET name=:P{$writer->builder->c}name,cdn_id=:P{$writer->builder->c}cdn_id,cdn_url=:P{$writer->builder->c}cdn_url
                WHERE id={$writer->temp_var};
            ")->push_param(":P{$writer->builder->c}id", $data['id']);
        } else {
            $writer->builder->push("INSERT INTO media__preplay__video(name,cdn_id,cdn_url) VALUES(:P{$writer->builder->c}name,:P{$writer->builder->c}cdn_id,:P{$writer->builder->c}cdn_url);")
                    ->push("SET {$writer->temp_var}=LAST_INSERT_ID();");
        }
        $writer->builder->push_params([
            ":P{$writer->builder->c}name" => $data['name'],
            ":P{$writer->builder->c}cdn_id" => $data['cdn_id'],
            ":P{$writer->builder->c}cdn_url" => $data['cdn_url'],
        ]);
        $writer->builder->inc_counter();
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

}
