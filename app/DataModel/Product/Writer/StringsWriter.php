<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Writer;

class StringsWriter {

    public function __construct() {
        ;
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $raw = \Filters\FilterManager::F()->apply_filter_datamap($input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw);
        $b->inc_counter();
        $b->push("INSERT INTO catalog__product__strings(id,name,description,consists)
            VALUES({$var},:P{$b->c}name,:P{$b->c}description,:P{$b->c}consists)
            ON DUPLICATE KEY UPDATE name=VALUES(name),description=VALUES(description),consists=VALUES(consists);
            ");
        $b->push_params([
            ":P{$b->c}name" => $raw['name'],
            ":P{$b->c}description" => $raw['description'],
            ":P{$b->c}consists" => $raw['consists'],
        ]);
        $b->inc_counter();
    }

    protected function get_filters() {
        return [
            'name' => ['Strip', 'Trim', 'NEString'],
            'description' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'consists' => ['Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

    public static function F(): StringsWriter {
        return new static();
    }

}
