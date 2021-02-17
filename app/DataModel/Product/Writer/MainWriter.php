<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Writer;

class MainWriter {

    public function __construct() {
        ;
    }

    protected function generate_alias(array $raw, \DB\SQLTools\SQLBuilder $b) {
        if (!$raw['alias']) {
            $raw['alias'] = \Helpers\Helpers::translit($raw['name']);
        }
        return \Helpers\Helpers::uniqueAlias("catalog__product", $raw['alias'], $raw['id'], $b->adapter, 'alias', 'id');
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $raw = \Filters\FilterManager::F()->apply_filter_datamap($input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw);

        $raw['alias'] = $this->generate_alias($raw, $b);

        $b->inc_counter();
        if ($raw['id']) {
            $b->push("SET {$var} = :P{$b->c}id;");
            $b->push("UPDATE catalog__product SET
                guid=" . ($raw['guid'] ? ":P{$b->c}guid" : "UUID()") . ",
                alias=:P{$b->c}alias,
                article=:P{$b->c}article,
                enabled=:P{$b->c}enabled,
                html_mode_c=:P{$b->c}html_mode_c,
                html_mode_d=:P{$b->c}html_mode_d,
                orderable=:P{$b->c}orderable,
                default_image=:P{$b->c}default_image,
                sort=:P{$b->c}sort
                WHERE id={$var};");
            $b->push_param(":P{$b->c}id", $raw['id']);
        } else {
            $b->push("INSERT INTO catalog__product (guid,alias,article,enabled,
                orderable,html_mode_c,html_mode_d,default_image,sort)
                VALUES(" . ($raw['guid'] ? ":P{$b->c}guid" : "UUID()") . ",
                :P{$b->c}alias,:P{$b->c}article,:P{$b->c}enabled,:P{$b->c}orderable,
                :P{$b->c}html_mode_c,:P{$b->c}html_mode_d,:P{$b->c}default_image,:P{$b->c}sort);");
            $b->push("SET {$var} = LAST_INSERT_ID();");
        }
        $b->push_params([
            ":P{$b->c}alias" => $raw['alias'],
            ":P{$b->c}article" => $raw['article'],
            ":P{$b->c}enabled" => $raw['enabled'],
            ":P{$b->c}orderable" => $raw['orderable'],
            ":P{$b->c}html_mode_c" => $raw['html_mode_c'],
            ":P{$b->c}html_mode_d" => $raw['html_mode_d'],
            ":P{$b->c}default_image" => $raw['default_image'],
            ":P{$b->c}sort" => $raw['sort'],
        ]);
        $raw['guid'] ? $b->push_param(":P{$b->c}guid", $raw['guid']) : 0;
        $b->inc_counter();
        $props = \DataModel\Product\Model\PropertyCollection::F();
        $props->load_from_object_array($raw['properties']);
        $props->save($b, $var);
        $b->inc_counter();
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'guid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'alias' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'article' => ['Strip', 'Trim', 'NEString'],
            'enabled' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'html_mode_c' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'html_mode_d' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'orderable' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'sort' => ['Int', 'Default0'],
            'properties' => ['NEArray', 'DefaultEmptyArray'],            
        ];        
    }

    /**
     * 
     * @return \DataModel\Product\Writer\MainWriter
     */
    public static function F(): MainWriter {
        return new static();
    }

}
