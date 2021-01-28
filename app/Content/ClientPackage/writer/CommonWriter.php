<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ClientPackage\writer;

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
     * @return \Content\ClientPackage\writer\CommonWriter
     */
    public static function F(): CommonWriter {
        return new static();
    }

    public function run(writer $w) {

        $writer_data = \Filters\FilterManager::F()->apply_filter_datamap($w->data_input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($writer_data);
        $this->create_sql($writer_data, $w);
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', "DefaultNull"], //integer            
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'price' => ['Float'], //string
            'days' => ['IntMore0', 'Trim', 'NEString'], //bool
            'usages' => ['IntMore0',], //bool
            'active' => ['Boolean', 'DefaultTrue'], //\DateTime            
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'features' => ['NEArray', 'DefaultEmptyArray'], //string                                 
        ];
    }

    protected function create_sql(array $data, writer $w) {
        $w->builder->push_params([
            ":P{$w->builder->c}name" => $data["name"],
            ":P{$w->builder->c}price" => $data["price"],
            ":P{$w->builder->c}usages" => $data["usages"],
            ":P{$w->builder->c}days" => $data["days"],
            ":P{$w->builder->c}active" => $data["active"]?1:0,
            ":P{$w->builder->c}default_image" => $data["default_image"],            
        ]);
        if ($data['id']) {
            $w->builder->push("SET {$w->temp_var} = :P{$w->builder->c}id;");
            $w->builder->push_param(":P{$w->builder->c}id", $data["id"]);
            $w->builder->push("UPDATE fitness__package SET name=:P{$w->builder->c}name,
                price=:P{$w->builder->c}price,
                days=:P{$w->builder->c}days,
                    usages=:P{$w->builder->c}usages,
                        active=:P{$w->builder->c}active,
                default_image=:P{$w->builder->c}default_image                 
                WHERE id={$w->temp_var};    
            ");
        } else {
            $w->builder->push("INSERT INTO fitness__package( name,price,days,usages,active,default_image )
                VALUES(:P{$w->builder->c}name,:P{$w->builder->c}price,:P{$w->builder->c}days,:P{$w->builder->c}usages,
                :P{$w->builder->c}active,:P{$w->builder->c}default_image);
                ");
            $w->builder->push("SET {$w->temp_var} = LAST_INSERT_ID();");
        }
        $w->builder->inc_counter();
    }

}
