<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Writer;

class ColorsWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \DataModel\Product\Writer\ColorsWriter
     */
    public static function F(): ColorsWriter {
        return new static();
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $colors = $input->get_filtered("colors", ['NEArray', 'DefaultEmptyArray']);
        $clean_colors = [];
        foreach ($colors as $color) {
            if (is_array($color)) {
                $raw_color = \Filters\FilterManager::F()->apply_filter_array($color, $this->get_filters());
                if (\Filters\FilterManager::F()->is_values_ok($raw_color)) {
                    $clean_colors[] = $raw_color;
                }
            }
        }
        //
        $b->inc_counter(); //цвета не связанные - можно удалять?
        //и синхронизировать цвета с храном цветов?
        $b->push("DELETE FROM catalog__product__color WHERE product_id={$var};");
        $inserts = [];
        $ic = 0;
        $params = [];
        $inserts2 = [];
        $params2 = [];
        foreach ($clean_colors as $color) {
            $inserts[] = "(:P{$b->c}_{$ic}_guid,{$var},:P{$b->c}_{$ic}_exchange_uid,:P{$b->c}_{$ic}_html_color,:P{$b->c}_{$ic}_sort)";
            $params[":P{$b->c}_{$ic}_guid"] = $color['guid'];
            $params[":P{$b->c}_{$ic}_exchange_uid"] = $color['exchange_uid'];
            $params[":P{$b->c}_{$ic}_exchange_uid"] = $color['exchange_uid'];
            $params[":P{$b->c}_{$ic}_html_color"] = $color['html_color'];
            $params[":P{$b->c}_{$ic}_sort"] = $color['sort'];
            $inserts2[] = "(:P{$b->c}_{$ic}_guid,:P{$b->c}_{$ic}_name)";
            $params2[":P{$b->c}_{$ic}_guid"] = $color['guid'];
            $params2[":P{$b->c}_{$ic}_name"] = $color['name'];
            $ic++;
        }
        if (count($inserts)) {
            $b->push(sprintf("INSERT INTO catalog__product__color(guid,product_id,exchange_uid,html_color,sort) VALUES %s 
                ON DUPLICATE KEY UPDATE exchange_uid=VALUES(exchange_uid),sort=VALUES(sort),html_color=VALUES(html_color);", implode(",", $inserts)));
            $b->push_params($params);
        }
        if (count($inserts2)) {
            $b->push(sprintf("INSERT INTO catalog__product__color__strings(guid,name) VALUES %s 
                ON DUPLICATE KEY UPDATE name=VALUES(name);", implode(",", $inserts2)));
            $b->push_params($params2);
        }
        $b->inc_counter();
    }

    protected function get_filters() {
        return [
            'guid' => ['Strip', 'Trim', 'NEString'],
            'exchange_uid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'html_color' => ['Strip', 'Trim', 'NEString'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'sort' => ['Int', 'Default0'],
        ];
    }

}
