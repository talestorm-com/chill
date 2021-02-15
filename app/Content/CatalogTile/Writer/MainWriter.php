<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile\Writer;

class MainWriter {

    public function __construct() {
        ;
    }

    protected function generate_alias(array $raw, \DB\SQLTools\SQLBuilder $b) {
        if (!$raw['alias']) {
            $raw['alias'] = \Helpers\Helpers::translit($raw['title']);
        }
        return \Helpers\Helpers::uniqueAlias("catalog__tile", $raw['alias'], $raw['id'], $b->adapter, 'alias', 'id');
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $raw = \Filters\FilterManager::F()->apply_filter_datamap($input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw);

        $raw['alias'] = $this->generate_alias($raw, $b);

        $b->inc_counter();
        if ($raw['id']) {
            $b->push("SET {$var} = :P{$b->c}id;");
            $b->push("UPDATE catalog__tile SET                
                alias=:P{$b->c}alias,
                title=:P{$b->c}title,
                info=:P{$b->c}info,
                visible=:P{$b->c}visible,
                loader=:P{$b->c}loader,
                template=:P{$b->c}template,
                crop=:P{$b->c}crop,
                crop_fill=:P{$b->c}crop_fill,
                background=:P{$b->c}background,
                show_header=:P{$b->c}show_header,
                css_class=:P{$b->c}css_class,
                ignore_product_visibility=:P{$b->c}ignore_product_visibility,
                ignore_catalog_visibility=:P{$b->c}ignore_catalog_visibility
                WHERE id={$var};");
            $b->push_param(":P{$b->c}id", $raw['id']);
        } else {
            $b->push("INSERT INTO catalog__tile (alias,title,info,visible,loader,
                template,crop,crop_fill,background,show_header,css_class,
                ignore_catalog_visibility,ignore_product_visibility)
                VALUES(:P{$b->c}alias,:P{$b->c}title,:P{$b->c}info,:P{$b->c}visible,
                :P{$b->c}loader,:P{$b->c}template,:P{$b->c}crop,:P{$b->c}crop_fill,
                :P{$b->c}background,:P{$b->c}show_header,:P{$b->c}css_class,
                :P{$b->c}ignore_catalog_visibility,:P{$b->c}ignore_product_visibility);");
            $b->push("SET {$var} = LAST_INSERT_ID();");
        }
        $b->push_params([
            ":P{$b->c}alias" => $raw['alias'],
            ":P{$b->c}title" => $raw['title'],
            ":P{$b->c}info" => $raw['info'],
            ":P{$b->c}visible" => $raw['visible'],
            ":P{$b->c}loader" => $raw['loader'],
            ":P{$b->c}template" => $raw['template'],
            ":P{$b->c}crop" => $raw['crop'],
            ":P{$b->c}crop_fill" => $raw['crop_fill'],
            ":P{$b->c}background" => $raw['background'],
            ":P{$b->c}show_header" => $raw['show_header'],
            ":P{$b->c}css_class" => $raw['css_class'],
            ":P{$b->c}ignore_catalog_visibility" => $raw['ignore_catalog_visibility'],
            ":P{$b->c}ignore_product_visibility" => $raw['ignore_product_visibility'],
        ]);
        $b->inc_counter();
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'alias' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'title' => ['Strip', 'Trim', 'NEString'],
            'info' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'visible' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'loader' => ['Strip', 'Trim', 'NEString'],
            'template' => ['Strip', 'Trim', 'NEString'],
            'crop' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'crop_fill' => ['Boolean', 'DefaultFalse', 'SQLBool'],
            'background' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'show_header' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'css_class' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'ignore_catalog_visibility' => ['Boolean', 'DefaultFalse', 'SQLBool'],
            'ignore_product_visibility' => ['Boolean', 'DefaultFalse', 'SQLBool'],
        ];
    }

    /**
     * 
     * @return \Content\CatalogTile\Writer\MainWriter
     */
    public static function F(): MainWriter {
        return new static();
    }

}
