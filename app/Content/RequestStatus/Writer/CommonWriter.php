<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\RequestStatus\Writer;

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
     * @return \Content\RequestStatus\WriterCommonWriter
     */
    public static function F(): CommonWriter {
        return new static();
    }

    public function run(Writer $w) {
        $writer_data = \Filters\FilterManager::F()->apply_filter_datamap($w->data_input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($writer_data);
        $this->create_sql($writer_data, $w);
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', "DefaultNull"], //integer            
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'color' => ['Strip', 'Trim', 'NEString', 'HTMLColor','DefaultHTMLColor'], //string            
            'final' => ['Boolean', 'DefaultFalse'], //bool 
            'sort' => ['Int', 'Default0'], //bool 
        ];
        
    }

    protected function create_sql(array $data, Writer $w) {        
        $w->builder->push_params([
            ":P{$w->builder->c}name" => $data["name"],
            ":P{$w->builder->c}color" => $data["color"],
                    ":P{$w->builder->c}sort" => $data["sort"],
            ":P{$w->builder->c}final" => $data["final"] ? 1 : 0,            
        ]);
        if ($data['id']) {
            $w->builder->push("SET {$w->temp_var} = :P{$w->builder->c}id;");
            $w->builder->push_param(":P{$w->builder->c}id", $data["id"]);
            $w->builder->push("UPDATE request__status SET name=:P{$w->builder->c}name,color=:P{$w->builder->c}color,
                final=:P{$w->builder->c}final,sort=:P{$w->builder->c}sort
                WHERE id={$w->temp_var};    
            ");
        } else {
            $w->builder->push("INSERT INTO request__status(name,color,final,sort )
                VALUES(:P{$w->builder->c}name,:P{$w->builder->c}color,:P{$w->builder->c}final,:P{$w->builder->c}sort);
                ");
            $w->builder->push("SET {$w->temp_var} = LAST_INSERT_ID();");
        }
        $w->builder->inc_counter();
    }

}
