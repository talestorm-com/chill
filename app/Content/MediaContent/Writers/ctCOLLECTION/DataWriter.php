<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctCOLLECTION;

/**
 * Description of VideoDataWriter
 *
 * @author eve
 */
class DataWriter {
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
        $writer->builder->push("INSERT INTO media__content__collection (id,common_name,default_poster, meta_title, meta_description, additional_content,translit_name_collection) 
            VALUES({$writer->temp_var}, :P{$writer->builder->c}common_name,                
                    :P{$writer->builder->c}default_poster, :P{$writer->builder->c}meta_title, :P{$writer->builder->c}meta_description, :P{$writer->builder->c}additional_content, :P{$writer->builder->c}translit_name_collection) 
            ON DUPLICATE KEY UPDATE common_name=VALUES(common_name),default_poster=VALUES(default_poster),meta_title=VALUES(meta_title),meta_description=VALUES(meta_description),additional_content=VALUES(additional_content),translit_name_collection=VALUES(translit_name_collection);    
            ")->push_params([
            ":P{$writer->builder->c}common_name" => $raw_data["common_name"],            
            ":P{$writer->builder->c}default_poster" => $raw_data["default_poster"],
            ":P{$writer->builder->c}meta_title" => $raw_data["meta_title"],
            ":P{$writer->builder->c}meta_description" => $raw_data["meta_description"],
            ":P{$writer->builder->c}additional_content" => $raw_data["additional_content"],            
            ":P{$writer->builder->c}translit_name_collection" => $this->createTranslitName($raw_data["common_name"]),
        ])->inc_counter();
    }

    protected function get_filters() {
        return [
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'meta_title' => ['NEString', 'DefaultEmptyString'],
            'meta_description' => ['NEString', 'DefaultEmptyString'],
            'additional_content' => ['NEString', 'DefaultEmptyString'],
            'translit_name_collection' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
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
