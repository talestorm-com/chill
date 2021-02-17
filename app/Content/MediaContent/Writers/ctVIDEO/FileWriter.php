<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctVIDEO;

/**
 * Description of FileWriter
 *
 * @author eve
 */
class FileWriter {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(Writer $writer) {

        $items = $writer->input->get_filtered("files", ['NEArray', 'DefaultEmptyArray']);
        $writer->builder->inc_counter();
        $writer->builder->push("DELETE FROM media__content__cdn__file WHERE id={$writer->temp_var};");
        $itw = [];
        foreach ($items as $row) {
            $row_item = \Filters\FilterManager::F()->apply_filter_array(is_array($row) ? $row : [], $this->get_filters());
            \Filters\FilterManager::F()->raise_array_error($row_item);
            $itw[] = $row_item;
        }
        if (count($itw)) {
            $writer->builder->inc_counter();
            $i = [];
            $p = [];
            $c = 0;
            foreach ($itw as $item) {
                $c++;
                $i[] = "({$writer->temp_var},
                    :P{$writer->builder->c}_i{$c}_cdn_id,
                    :P{$writer->builder->c}_i{$c}_enabled,
                    :P{$writer->builder->c}_i{$c}_content_type,
                    :P{$writer->builder->c}_i{$c}_size,
                    :P{$writer->builder->c}_i{$c}_info)";
                $p = array_merge($p, [
                    ":P{$writer->builder->c}_i{$c}_cdn_id" => $item["cdn_id"],
                    ":P{$writer->builder->c}_i{$c}_enabled" => $item['enabled'],
                    ":P{$writer->builder->c}_i{$c}_content_type" => $item["content_type"],
                    ":P{$writer->builder->c}_i{$c}_size" => $item["size"],
                    ":P{$writer->builder->c}_i{$c}_info" => $item["info"],
                ]);
                $c++;
            }
            if (count($i)) {
                $writer->builder->push(sprintf("INSERT INTO media__content__cdn__file(id,cdn_id,`enabled`,content_type,size,info) VALUES %s 
                   ON DUPLICATE KEY UPDATE `enabled`=VALUES(`enabled`),content_type=VALUES(content_type),size=VALUES(size),info=VALUES(info);
                   ", implode(",", $i)))->push_params($p)->inc_counter();
            }
        }
        $writer->builder->inc_counter();
    }

    protected function get_filters() {
        return [
            'cdn_id' => ['Strip', 'Trim', 'NEString'],
            'content_type' => ['Strip', 'Trim', 'NEString'],
            'size' => ['Strip', 'Trim', 'NEString'],
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'enabled' => ['Boolean', 'DefaultFalse', 'SQLBool'],
        ];
    }

}
