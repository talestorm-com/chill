<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Ribbon\Writer;

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
     * @return \Content\Ribbon\Writer\CommonWriter
     */
    public static function F(): CommonWriter {
        return new static();
    }

    public function run(RibbonItemWriter $w) {

        $writer_data = \Filters\FilterManager::F()->apply_filter_datamap($w->data_input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($writer_data);
        $this->create_sql($writer_data, $w);
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', "DefaultNull"], //integer            
            'title' => ['Strip', 'Trim', 'NEString'], //string            
            'active' => ['Boolean', 'DefaultFalse'], //bool
            'html_mode' => ['Boolean', 'DefaultTrue'], //bool
            'html_mode_c' => ['Boolean', 'DefaultTrue'], //bool
            'published' => ['DateMatch', 'DefaultNull'], //\DateTime                        
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string                                 
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string                                 
        ];
    }

    protected function create_sql(array $data, RibbonItemWriter $w) {
        $w->builder->push_params([
            ":P{$w->builder->c}title" => $data["title"],
            ":P{$w->builder->c}active" => $data["active"] ? 1 : 0,
            ":P{$w->builder->c}html_mode" => $data["html_mode"] ? 1 : 0,
            ":P{$w->builder->c}html_mode_c" => $data["html_mode_c"] ? 1 : 0,
            ":P{$w->builder->c}published" => $data["published"] ? $data['published']->format('Y-m-d H:i:s') : null,
            ":P{$w->builder->c}info" => $data["info"],
            ":P{$w->builder->c}intro" => $data["intro"],
            ":P{$w->builder->c}info_length" => mb_strlen(trim(strip_tags($data["info"])), 'UTF-8'),
            ":P{$w->builder->c}intro_length" => mb_strlen(trim(strip_tags($data["intro"])), 'UTF-8'),
        ]);
        if ($data['id']) {
            $w->builder->push("SET {$w->temp_var} = :P{$w->builder->c}id;");
            $w->builder->push_param(":P{$w->builder->c}id", $data["id"]);
            $w->builder->push("UPDATE ribbon SET 
                title=:P{$w->builder->c}title,
                active=:P{$w->builder->c}active,
                html_mode=:P{$w->builder->c}html_mode,
                html_mode_c=:P{$w->builder->c}html_mode_c,
                published=:P{$w->builder->c}published,
                info=:P{$w->builder->c}info,
                intro=:P{$w->builder->c}intro,
                info_length=:P{$w->builder->c}info_length,
                intro_length=:P{$w->builder->c}intro_length
                        
                WHERE id={$w->temp_var};    
            ");
        } else {
            $w->builder->push("INSERT INTO ribbon( title,active,html_mode,html_mode_c,published,info,intro,info_length,intro_length )
                VALUES(
                :P{$w->builder->c}title,:P{$w->builder->c}active,:P{$w->builder->c}html_mode,:P{$w->builder->c}html_mode_c,:P{$w->builder->c}published,
                :P{$w->builder->c}info,:P{$w->builder->c}intro,:P{$w->builder->c}info_length,:P{$w->builder->c}intro_length
                );
                ");
            $w->builder->push("SET {$w->temp_var} = LAST_INSERT_ID();");
        }
        $w->builder->inc_counter();
    }

}
