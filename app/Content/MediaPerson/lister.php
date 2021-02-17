<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaPerson;

/**
 * Description of lister
 *
 * @author eve
 */
class lister extends \Content\Lister\Lister {

    protected function get_sort_separator(): string {
        return "|";
    }

    protected function get_sorts(): array {
        return [
            'id' => 'A.id',
            'name_common_name_en' => "A.common_name|A.id",
            'name_common_name' => 'COALESCE(B.name,C.name)|A.id',
            'name'=>"CONCAT(COALESCE(A.common_name,''),' ',COALESCE(B.name,C.name,''))"
            
        ];
    }

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'name_common_name_en' => 'String:A.common_name',
            'name_common_name' => 'String:COALESCE(B.name,C.name)',
            'name'=>"String:CONCAT(COALESCE(A.common_name,''),' ',COALESCE(B.name,C.name,''))",
        ];
    }

    protected function build_query() {
        return sprintf("SELECT SQL_CALC_FOUND_ROWS A.id,A.common_name name_en,COALESCE(B.name,C.name) name
            FROM media__content__actor A
            LEFT JOIN media__content__actor__strings_lang_%s B ON(A.id=B.id)
            LEFT JOIN media__content__actor__strings_lang_%s C ON(A.id=C.id)
            %s %s %s %s", \Language\LanguageList::F()->get_current_language()->id,
                \Language\LanguageList::F()->get_default_language()->id, $this->filter->whereWord,
                $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
