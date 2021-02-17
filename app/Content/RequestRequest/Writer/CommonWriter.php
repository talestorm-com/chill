<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\RequestRequest\Writer;

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
            'id' => ['IntMore0',], //integer            
            'company_name' => ['Strip', 'Trim', 'NEString'], //string
            'company_address' => ['Strip', 'Trim', 'NEString'], //string
            'requisites' => ['Strip', 'Trim', 'NEString'], //string
            'position_name' => ['Strip', 'Trim', 'NEString'], //string
            'position_cost' => ["Float"],
            'nds_pc' => ["Float"],
            'nds_eur' => ["Float"],
        ];
    }

    protected function create_sql(array $data, Writer $w) {
        $w->builder->push_params([
            ":P{$w->builder->c}company_name" => $data["company_name"],
            ":P{$w->builder->c}company_address" => $data["company_address"],
            ":P{$w->builder->c}requisites" => $data["requisites"],
            ":P{$w->builder->c}position_name" => $data["position_name"],
            ":P{$w->builder->c}position_cost" => $data["position_cost"],
                    ":P{$w->builder->c}nds_pc" => $data["nds_pc"],
                            ":P{$w->builder->c}nds_eur" => $data["nds_eur"],
        ]);
        if ($data['id']) {
            $w->builder->push("SET {$w->temp_var} = :P{$w->builder->c}id;");
            $w->builder->push_param(":P{$w->builder->c}id", $data["id"]);
            $w->builder->push("UPDATE request SET company_name=:P{$w->builder->c}company_name,
                company_address=:P{$w->builder->c}company_address,
                requisites=:P{$w->builder->c}requisites,
                position_name=:P{$w->builder->c}position_name,
                position_cost=:P{$w->builder->c}position_cost,
                nds_pc=:P{$w->builder->c}nds_pc,
                nds_eur=:P{$w->builder->c}nds_eur     
                WHERE id={$w->temp_var};    
            ");
        } else {
            \Errors\common_error::R("invalid request_id");
        }
        $w->builder->inc_counter();
    }

}
