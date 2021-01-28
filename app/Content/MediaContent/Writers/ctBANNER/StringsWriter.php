<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctBANNER;

/**
 * Description of StringsWriter
 *
 * @author eve
 */
class StringsWriter {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(Writer $writer) {
        $writer->builder->inc_counter();
        $writer->builder->push("DELETE FROM media__content__banner__strings WHERE id={$writer->temp_var};");
        $writer->builder->inc_counter();
        $names = $writer->input->get_filtered('strings', ['NEArray', 'DefaultEmptyArray']);
        $cnames = [];
        foreach ($names as $names_row) {
            try {
                $cname = \Filters\FilterManager::F()->apply_filter_array($names_row, $this->get_filters());
                \Filters\FilterManager::F()->raise_array_error($cname);
                if ($cname['url'] || $cname['bannertext']) {
                    $cnames[] = $cname;
                }
            } catch (\Throwable $e) {
                
            }
        }        
        if (count($cnames)) {
            $i = [];
            $p = [];
            $c = 0;
            foreach ($cnames as $cname) {
                $c++;
                $i[] = "({$writer->temp_var},:P{$writer->builder->c}_i{$c}language_id,:P{$writer->builder->c}_i{$c}url,:P{$writer->builder->c}_i{$c}bannertext)";
                $p = array_merge($p, [
                    ":P{$writer->builder->c}_i{$c}language_id" => $cname["language_id"],
                    ":P{$writer->builder->c}_i{$c}url" => $cname["url"],
                    ":P{$writer->builder->c}_i{$c}bannertext" => $cname["bannertext"],
                ]);
                $c++;
            }
            if (count($i)) {
                $writer->builder->inc_counter();
                $writer->builder->push(sprintf("INSERT INTO media__content__banner__strings(id,language_id,url,bannertext) VALUES %s ON DUPLICATE KEY UPDATE url=VALUES(url),bannertext=VALUES(bannertext);", implode(",", $i)))
                        ->push_params($p);
            }
        }
        $writer->builder->inc_counter();
    }

    protected function get_filters() {
        return [
            'language_id' => ['Strip', 'Trim', 'NEString'],
            'bannertext' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
