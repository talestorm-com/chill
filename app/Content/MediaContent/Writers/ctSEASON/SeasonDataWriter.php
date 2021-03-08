<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctSEASON;

/**
 * Description of VideoDataWriter
 *
 * @author eve
 */
class SeasonDataWriter {
    //put your code here

    const RUS = [
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
    ];
    const LAT = [
        'A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya'
    ];
    const SIMBL = [
        ' ',',','.','\'','"','#','«','»',';',':','_','=','+','(','^','%','$','@',')','{','}','[',']','<','>'
    ];
    const SIMBL2 = [
        '!','?'
    ];

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static ();
    }

    public function run(Writer $writer) {
        $raw_data = \Filters\FilterManager::F()->apply_filter_datamap($writer->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw_data);
        $writer->builder->inc_counter();
        $writer->builder->push("INSERT INTO media__content__season (id,common_name,default_poster,eng_name,released,origin_language,copyright_holder,translit_name) 
            VALUES({$writer->temp_var}, :P{$writer->builder->c}common_name,:P{$writer->builder->c}default_poster,:P{$writer->builder->c}eng_name,
               :P{$writer->builder->c}released,:P{$writer->builder->c}origin_language,:P{$writer->builder->c}copyright_holder,:P{$writer->builder->c}translit_name
                ) 
            ON DUPLICATE KEY UPDATE common_name=VALUES(common_name),default_poster=VALUES(default_poster),eng_name=VALUES(eng_name),
            released=VALUES(released),origin_language=VALUES(origin_language),copyright_holder=VALUES(copyright_holder),translit_name=VALUES(translit_name);
            ")->push_params([
            ":P{$writer->builder->c}common_name" => $raw_data["common_name"],
            ":P{$writer->builder->c}default_poster" => $raw_data["default_poster"],
            ":P{$writer->builder->c}eng_name" => $raw_data["eng_name"],
            ":P{$writer->builder->c}copyright_holder" => $raw_data["copyright_holder"],
            ":P{$writer->builder->c}translit_name" => $this->createTranslitName($raw_data["common_name"]),
            ":P{$writer->builder->c}origin_language" => $raw_data["origin_language"],
            ":P{$writer->builder->c}released" => $raw_data["released"] ? $raw_data['released']->format('Y-m-d H:i:s') : null,
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'eng_name' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'origin_language' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'released' => ['DateMatch', 'DefaultNull'],
            'copyright_holder' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'translit_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }
    protected function createTranslitName($name)
    {
        $translName = mb_strtolower(str_replace(self::RUS, self::LAT, $name));
        $translName = (str_replace(self::SIMBL, '-', $translName));
        $translName = (str_replace(self::SIMBL2, '', $translName));
        $translName = trim($translName, '-');
        $translName = preg_replace('/(\-){2,}/', '$1', $translName);
        return $translName;
    }
}
