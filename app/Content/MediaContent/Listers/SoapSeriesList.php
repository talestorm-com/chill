<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Listers;

/**
 * Description of SoapSeriesList
 * листер серий по сериалу (без сезонов)
 * @author eve
 */
class SoapSeriesList extends \Content\Lister\Lister {

    protected function get_sort_separator(): string {
        return '|';
    }

    protected function get_filters(): array {
        return [
            'soap_id' => 'Int:D.id',
            'named_season_common_name'=>'String:C.common_name',
            'named_season_name'=>'String:COALESCE(SEASONSTRINGS1.name,SEASONSTRINGS2.name)',
            'named_series_common_name'=>'String:B.common_name',
            'named_series_name'=>'String:COALESCE(SERIESSTRINGS1.name,SERIESSTRINGS2.name)',
            'enabled'=>'Int:AB.enabled',
            'season_enabled'=>'Int:AC.enabled',
            'id'=>'Int:AB.id'
        ];
    }

    protected function get_sorts(): array {
        return [
            'id'=>'AB.id',
            'named_season_common_name'=>'C.common_name|AB.id',
            'named_season_name'=>'COALESCE(SEASONSTRINGS1.name,SEASONSTRINGS2.name)|AB.id',
            'named_series_common_name'=>'B.common_name|AB.id',
            'named_series_name'=>'COALESCE(SERIESSTRINGS1.name,SERIESSTRINGS2.name)|AB.id',
            'enabled'=>'AB.enabled|AB.id',
            'season_enabled'=>'AC.enabled|AB.id',            
        ];
    }

    protected function build_query() {
        $lang = \Language\LanguageList::F()->get_current_language();
        $dlang = \Language\LanguageList::F()->get_default_language();
        $query_tpl = " SELECT SQL_CALC_FOUND_ROWS
            AB.id,AB.enabled,B.common_name series_common_name,
            COALESCE(SERIESSTRINGS1.name,SERIESSTRINGS2.name) series_name,
            C.common_name season_common_name,
            COALESCE(SEASONSTRINGS1.name,SEASONSTRINGS2.name) season_name,
            AC.enabled season_enabled
            FROM media__content AB JOIN media__content__season__series B ON(AB.id=B.id)
            JOIN media__content__season__season C ON(C.id=B.seasonseason_id)
            JOIN media__content__season D ON(D.id=C.season_id)
            JOIN media__content AC ON(AC.id=C.id)            
            LEFT JOIN media__content_seasonseries_strings__lang_%s SERIESSTRINGS1 ON(SERIESSTRINGS1.id=B.id)
            LEFT JOIN media__content_seasonseries_strings__lang_%s SERIESSTRINGS2 ON(SERIESSTRINGS2.id=B.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s SEASONSTRINGS1 ON(SEASONSTRINGS1.id=C.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s SEASONSTRINGS2 ON(SEASONSTRINGS2.id=C.id)                        
            %s %s %s %s            
            ";
        return sprintf($query_tpl, $lang, $dlang, $lang, $dlang, $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
