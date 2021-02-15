<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Promo;

/**
 * Description of Writer
 *
 * @author eve
 */
class Writer extends \Content\MediaContent\Writers\AWriter {

    /**
     * 
     * @return $this
     */
    public function run() {
        $this->builder->inc_counter();
        $cdata = \Filters\FilterManager::F()->apply_filter_datamap($this->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($cdata);
        $cdata['value'] < 1 ? $cdata['value'] = 1 : 0;
        $test = null;
        try {
            $test = Promo::F(null, $cdata['code']);
        } catch (\Throwable $e) {
            $test = null;
        }
        if ($test && $test->id !== $cdata['id']) {
            \Errors\common_error::R("Такой код уже существует!");
        }
        $this->builder->inc_counter();
        if ($cdata['id']) {
            $this->builder->push("SET {$this->temp_var} = :P{$this->builder->c}id;")
                    ->push_param(":P{$this->builder->c}id", $cdata['id'])
                    ->push("UPDATE chill__promo SET name=:P{$this->builder->c}name,code=:P{$this->builder->c}code,`value`=:P{$this->builder->c}value
                        WHERE id={$this->temp_var};                        
                    ");
        } else {
            $this->builder->push("INSERT INTO chill__promo (name,code,`value`) 
                    VALUES(:P{$this->builder->c}name,:P{$this->builder->c}code,:P{$this->builder->c}value );
                        SET {$this->temp_var} = LAST_INSERT_ID();");
        }
        $this->builder->push_params([
            ":P{$this->builder->c}name" => $cdata['name'],
            ":P{$this->builder->c}code" => $cdata['code'],
            ":P{$this->builder->c}value" => $cdata['value'],
        ]);
        $this->builder->inc_counter();
        $this->result_id = $this->builder->execute_transact($this->temp_var);
        return $this;
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', 'Default0'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'code' => ['Strip', 'Trim', 'NEString'],
            'value' => ['Float'],
        ];
    }

}
