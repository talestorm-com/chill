<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Listers;

/**
 * Description of SoapSeasonsList
 *
 * @author eve
 */
class SoapSeasonsList extends \Content\Lister\Lister {

    protected function get_sort_separator(): string {
        return '|';
    }

    protected function get_filters(): array {
        return [
            'soap_id' => 'Int:AB.id',
            'named_season_name' => 'String:COALESCE(S1.name,S2.name)',
            'named_season_common_name' => 'String:C.common_name',
            'enabled' => 'Int:AC.enabled',
            'id' => 'Int:AC.id',
        ];
    }

    protected function get_sorts(): array {
        return [
            'id' => 'AC.id',
            'named_season_common_name' => 'C.common_name|AC.id',
            'named_season_name' => 'COALESCE(S1.name,S2.name)|AC.id',
            'enabled' => 'AC.enabled|AC.id',
        ];
    }

    protected function build_query() {
        $lang = \Language\LanguageList::F()->get_current_language();
        $dlang = \Language\LanguageList::F()->get_default_language();
        $query_tpl = " SELECT SQL_CALC_FOUND_ROWS
            AC.id,AC.enabled,C.common_name season_common_name,
            COALESCE(S1.name,S2.name) season_name
            FROM media__content AB 
            JOIN media__content__season B ON(B.id=AB.id)
            JOIN media__content__season__season C ON(C.season_id=B.id)
            JOIN media__content AC ON(AC.id=C.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s S1 ON(S1.id=C.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s S2 ON(S2.id=C.id)
            %s %s %s %s ";

        return sprintf($query_tpl, $lang, $dlang, $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
