<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctSEASONSERIES;

/**
 * Description of CommonDataWriter
 *
 * @author eve
 */
class CommonDataWriter {

    const CTYPE = "ctSEASONSERIES";

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
            $writer->builder->push("UPDATE media__content SET enabled=:P{$writer->builder->c}enabled,age_restriction=NULL,emoji=NULL
                WHERE id={$writer->temp_var};
            ")->push_param(":P{$writer->builder->c}id", $raw_data["id"]);
        } else {
            $writer->builder->push("INSERT INTO media__content(alias,ctype,enabled,age_restriction,emoji) VALUES(
                UUID(),:P{$writer->builder->c}ctype,:P{$writer->builder->c}enabled,NULL,NULL
                );")->push("SET {$writer->temp_var} = LAST_INSERT_ID();")->push_param(":P{$writer->builder->c}ctype", static::CTYPE);
        }
        $writer->builder->push_params([
            ":P{$writer->builder->c}enabled" => $raw_data["enabled"]
        ]);
        $writer->builder->inc_counter();
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'enabled' => ['Boolean', 'DefaultFalse', 'SQLBool'],
        ];
    }

}
