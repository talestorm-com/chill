<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\FilterPreset\Writer;

/**
 * Description of ItemsWriter
 *
 * @author eve
 */
class ItemsWriter {

    //put your code here
    //Нужен сабскрит на постобработку - что то вроде темпа
// сохраняем uid записанных в runtime - потом запустим процессор очистки


    protected function get_filters() {
        return[
            'uid' => ['Strip', 'Trim', 'NEString',],
            'name' => ['Strip', 'Trim', 'NEString',],
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'preset' => [ 'Trim', 'NEString',],
            'html_mode' => ['Boolean', 'DefaultTrue'],
            'sort' => ["Int", "Default0"],
            'image_removed' => ["Boolean", 'DefaultFalse'],
        ];
    }

    public function run(FilterPresetWriter $w) {
        $w->builder->inc_counter();
        $items = $w->data_input->get_filtered('items', ['NEArray', 'DefaultEmptyArray']);
        $writed_items = [];
        $do_remove_item_image = [];
        $params = [];
        $inserts = [];
        $ic = 0;
        $bc = $w->builder->c;
        $query_prototype = "INSERT INTO filterpreset__item (id,uid,name,image,sort,html_mode,info,preset) VALUES %s ON DUPLICATE KEY UPDATE
            name=VALUES(name),image=VALUES(image),sort=VALUES(sort),html_mode=VALUES(html_mode),info=VALUES(info),preset=VALUES(preset);
            ";
        foreach ($items as $item) {
            $item_c = \Filters\FilterManager::F()->apply_filter_array($item, $this->get_filters());
            \Filters\FilterManager::F()->raise_array_error($item_c);
            $inserts[] = "({$w->temp_var},:P{$bc}_{$ic}_uid,:P{$bc}_{$ic}_name,:P{$bc}_{$ic}_image,:P{$bc}_{$ic}_sort,:P{$bc}_{$ic}_html_mode,:P{$bc}_{$ic}_info,:P{$bc}_{$ic}_preset)";
            $params = array_merge($params, [
                ":P{$bc}_{$ic}_uid" => $item_c['uid'],
                ":P{$bc}_{$ic}_name" => $item_c['name'],
                ":P{$bc}_{$ic}_image" => $item_c['image_removed'] ? null : md5($item_c['uid']),
                ":P{$bc}_{$ic}_sort" => $item_c["sort"],
                ":P{$bc}_{$ic}_html_mode" => $item_c['html_mode'] ? 1 : 0,
                ":P{$bc}_{$ic}_info" => $item_c['info'],
                ":P{$bc}_{$ic}_preset" => $item_c['preset'],
            ]);
            if ($item_c['image_removed']) {
                $do_remove_item_image[] = $item_c['uid'];
            }
            $writed_items[] = $item_c["uid"];
            $ic++;
        }
        if (count($inserts)) {
            $w->builder->push(sprintf($query_prototype, implode(",", $inserts)));
            $w->builder->push_params($params);
        }
        $w->runtime->set("item_writer_writed", $writed_items);
        $w->runtime->set("item_writer_remove_img", $do_remove_item_image);
        $w->builder->inc_counter();
    }

    public function __construct() {
        ;
    }

    public static function F(): ItemsWriter {
        return new static();
    }

}
