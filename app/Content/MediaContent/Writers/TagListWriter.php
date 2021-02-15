<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers;

/**
 * Description of GenreListWriter
 *
 * @author eve
 */
class TagListWriter {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(AWriter $writer) {
        $genre_list_raw = $writer->input->get_filtered("tags", ["NEArray", "DefaultEmptyArray"]);
        $genre_lis = [];
        foreach ($genre_list_raw as $row) {
            if (is_array($row)) {
                try {
                    $genre = \Filters\FilterManager::F()->apply_filter_array($row, $this->get_filters());
                    \Filters\FilterManager::F()->raise_array_error($genre);
                    $genre_lis[] = $genre;
                } catch (\Throwable $e) {
                    
                }
            }
        }
        $writer->builder->inc_counter();
        $writer->builder->push("DELETE FROM media__content__tag__list WHERE media_id={$writer->temp_var};");
        $writer->builder->inc_counter();
        if (count($genre_lis)) {
            $i = [];
            $c = 0;
            $p = [];
            foreach ($genre_lis as $genre) {
                $c++;
                $i[] = "({$writer->temp_var},:P{$writer->builder->c}genre_i{$c},:P{$writer->builder->c}sort_i{$c})";
                $p = array_merge($p, [
                    ":P{$writer->builder->c}genre_i{$c}" => $genre['id'],
                    ":P{$writer->builder->c}sort_i{$c}" => $genre['sort'],
                ]);
            }
            if (count($i)) {
                $writer->builder->push(sprintf("INSERT INTO media__content__tag__list(media_id,tag_id,sort) VALUES %s ON DUPLICATE KEY UPDATE sort=VALUES(sort);", implode(",", $i)))
                        ->push_params($p)->inc_counter();
            }
        }        
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0'],
            'sort' => ['Int', 'Default0'],
        ];
    }

}
