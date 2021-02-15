<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctSEASON;

/**
 * Description of CommonDataWriter
 *
 * @author eve
 */
class CommonDataWriter {

    const CTYPE = "ctSEASON";

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
        if ($raw_data['id']) {
            $writer->builder->push("SET {$writer->temp_var} = :P{$writer->builder->c}id;");
            $writer->builder->push("UPDATE media__content SET enabled=:P{$writer->builder->c}enabled,
                age_restriction=:P{$writer->builder->c}age_restriction,emoji=:P{$writer->builder->c}emoji,
                track_language=:P{$writer->builder->c}track_language ,
                series_count=:P{$writer->builder->c}series_count,
                seasons_count=:P{$writer->builder->c}seasons_count,
                free=:P{$writer->builder->c}free
                    ,mcsort=:P{$writer->builder->c}mcsort
                WHERE id={$writer->temp_var};
            ")->push_param(":P{$writer->builder->c}id", $raw_data["id"]);
        } else {
            $writer->builder->push("INSERT INTO media__content(alias,ctype,enabled,age_restriction,emoji,track_language,series_count,seasons_count,free,mcsort) VALUES(
                UUID(),:P{$writer->builder->c}ctype,:P{$writer->builder->c}enabled,:P{$writer->builder->c}age_restriction,:P{$writer->builder->c}emoji,
                :P{$writer->builder->c}track_language,:P{$writer->builder->c}series_count,:P{$writer->builder->c}seasons_count,
                :P{$writer->builder->c}free,:P{$writer->builder->c}mcsort
                );")->push("SET {$writer->temp_var} = LAST_INSERT_ID();")->push_param(":P{$writer->builder->c}ctype", static::CTYPE);
        }
        $writer->builder->push_params([
            ":P{$writer->builder->c}enabled" => $raw_data["enabled"],
            ":P{$writer->builder->c}age_restriction" => $raw_data["age_restriction"],
            ":P{$writer->builder->c}emoji" => $raw_data["emoji"],
            ":P{$writer->builder->c}track_language" => $raw_data["track_language"],
            ":P{$writer->builder->c}series_count" => $raw_data["series_count"],
            ":P{$writer->builder->c}seasons_count" => $raw_data['seasons_count'],
            ":P{$writer->builder->c}free" => $raw_data['free'],
            ":P{$writer->builder->c}mcsort" => $raw_data['mcsort'],
        ]);
        $writer->builder->inc_counter();
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'enabled' => ['Boolean', 'DefaultFalse', 'SQLBool'],
            'free' => ['Boolean', 'DefaultFalse', 'SQLBool'],
            'age_restriction' => ['IntMore0', 'DefaultNull'],
            'emoji' => ['IntMore0', 'DefaultNull'],
            'track_language' => ['IntMore0', 'DefaultNull'],
            'series_count' => ['IntMore0', 'DefaultNull'],
            'seasons_count' => ['IntMore0', 'DefaultNull'],
            'mcsort' => ['Int', 'Default0'],
        ];
    }

}
