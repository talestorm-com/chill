<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\FilterPreset\Writer;

/**
 * Description of CommonWriter
 *
 * @author eve
 */
class CommonWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \Content\FilterPreset\Writer\CommonWriter
     */
    public static function F(): CommonWriter {
        return new static();
    }

    public function run(FilterPresetWriter $w) {

        $writer_data = \Filters\FilterManager::F()->apply_filter_datamap($w->data_input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($writer_data);
        $this->create_sql($writer_data, $w);
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', "DefaultNull"], //integer            
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'alias' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'active' => ['Boolean', 'DefaultFalse'], //bool
            'html_mode' => ['Boolean', 'DefaultTrue'], //bool
            'published' => ['DateMatch', 'DefaultNull'], //\DateTime
            'cost' => ['Float', 'Default0'], //double
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string                                 
        ];
    }

    protected function create_sql(array $data, FilterPresetWriter $w) {
        $data['alias'] = \Helpers\Helpers::uniqueAlias('filterpreset', \Helpers\Helpers::NEString($data['alias'], \Helpers\Helpers::translit($data['name'])), $data['id']);
        $w->builder->push_params([
            ":P{$w->builder->c}name" => $data["name"],
            ":P{$w->builder->c}alias" => $data["alias"],
            ":P{$w->builder->c}active" => $data["active"] ? 1 : 0,
            ":P{$w->builder->c}html_mode" => $data["html_mode"] ? 1 : 0,
            //":P{$w->builder->c}published" => $data["published"] ? $data['published']->format('Y-m-d H:i:s') : null,
            ":P{$w->builder->c}cost" => $data["cost"],
            ":P{$w->builder->c}default_image" => $data["default_image"],
            ":P{$w->builder->c}info" => $data["info"],
        ]);
        if ($data['id']) {
            $w->builder->push("SET {$w->temp_var} = :P{$w->builder->c}id;");
            $w->builder->push_param(":P{$w->builder->c}id", $data["id"]);
            $w->builder->push("UPDATE filterpreset SET name=:P{$w->builder->c}name,alias=:P{$w->builder->c}alias,
                active=:P{$w->builder->c}active,html_mode=:P{$w->builder->c}html_mode,
                cost=:P{$w->builder->c}cost,default_image=:P{$w->builder->c}default_image,
                info=:P{$w->builder->c}info
                WHERE id={$w->temp_var};    
            ");
        } else {
            $w->builder->push("INSERT INTO filterpreset( name,alias,active,html_mode,cost,default_image,info )
                VALUES(:P{$w->builder->c}name,:P{$w->builder->c}alias,:P{$w->builder->c}active,:P{$w->builder->c}html_mode,
                :P{$w->builder->c}cost,:P{$w->builder->c}default_image,:P{$w->builder->c}info);
                ");
            $w->builder->push("SET {$w->temp_var} = LAST_INSERT_ID();");
        }
        $w->builder->inc_counter();
    }

}
