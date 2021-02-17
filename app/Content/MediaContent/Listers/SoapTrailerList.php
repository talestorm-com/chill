<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Listers;

/**
 * Description of SoapTrailerList
 * листер трейлеров к сезонам сериала по сериалу
 * @author eve
 */
class SoapTrailerList extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return[
            'id' => 'Int:T.id',
            'named_trailer' => 'String:COALESCE(TS1.name,TS2.name)',
            'named_season_name' => 'String:COALESCE(SS1.name,SS2.name)',
            'named_season_common_name' => 'String:B.common_name',
            'enabled' => 'Int:A0.enabled',
            'season_enabled' => 'Int:A.enabled',
            'soap_enabled' => 'Int:A1.enabled',
            'soap_id' => 'Int:SP.id',
        ];
    }

    protected function get_sort_separator(): string {
        return '|';
    }

    protected function get_sorts(): array {
        return [
            'id' => 'T.id',
            'named_trailer' => 'COALESCE(TS1.name,TS2.name)|T.id',
            'named_season_name' => 'COALESCE(SS1.name,SS2.name)|T.id',
            'named_season_common_name' => 'B.common_name|T.id',
            'enabled' => 'A0.enabled|T.id',
            'season_enabled' => 'A.enabled|T.id',
            'soap_enabled' => 'A1.enabled|A.id',
        ];
    }

    protected function build_query() {

        $query = "
            SELECT SQL_CALC_FOUND_ROWS
            T.id, COALESCE(TS1.name,TS2.name) name,
            COALESCE(SS1.name,SS2.name) season_name,
            B.common_name season_common_name,
            A.enabled season_enabled,
            A0.enabled enabled,
            A1.enabled soap_enabled
            FROM media__content A JOIN media__content__season__season B ON(A.id=B.id)
            JOIN media__content__trailer T ON(T.content_id = B.id)
            JOIN media__content A0 ON (A0.id=T.id)
            JOIN media__content__season SP ON(SP.id=B.season_id)
            JOIN media__content A1 ON(A1.id=SP.id)
            LEFT JOIN media__content__trailer__strings TS1 ON(TS1.id=T.id and TS1.language_id='%s')
            LEFT JOIN media__content__trailer__strings TS2 ON(TS2.id=T.id and TS2.language_id='%s')
            LEFT JOIN media__content__seasonseason__strings__lang_%s SS1 ON(SS1.id=B.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s SS2 ON(SS2.id=B.id)
            
            %s %s %s %s
            
            ";
        $language = \Language\LanguageList::F()->get_current_language();
        $def_lang = \Language\LanguageList::F()->get_default_language();
        return sprintf($query, $language, $def_lang, $language, $def_lang, $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
