<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Writer;

class MetaWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \DataModel\Product\Writer\MetaWriter
     */
    public static function F(): MetaWriter {
        return new static();
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $meta = $input->get_filtered("meta", ['NEArray', 'DefaultEmptyArray']);
        $raw = \Filters\FilterManager::F()->apply_filter_array($meta, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw);
        $b->inc_counter();
        $b->push("INSERT INTO catalog__product__meta(id,title,keywords,description,og_title,og_description)
            VALUES({$var},:P{$b->c}title,:P{$b->c}keywords,:P{$b->c}description,:P{$b->c}og_title,:P{$b->c}og_description)
            ON DUPLICATE KEY UPDATE title=VALUES(title),description=VALUES(description),
            keywords=VALUES(keywords),og_title=VALUES(og_title),og_description=VALUES(og_description);
            ");
        $b->push_params([
            ":P{$b->c}title" => $raw['title'],
            ":P{$b->c}description" => $raw['description'],
            ":P{$b->c}keywords" => $raw['keywords'],
            ":P{$b->c}og_title" => $raw['og_title'],
            ":P{$b->c}og_description" => $raw['og_description'],
        ]);
        $b->inc_counter();
    }

    protected function get_filters() {
        return [
            'title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'description' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'keywords' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'og_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'og_description' => ['Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

}
