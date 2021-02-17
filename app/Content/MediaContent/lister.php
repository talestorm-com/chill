<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent;

/**
 * Description of lister
 *
 * @author eve
 */
class lister extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'common_name' => "String:CASE ctype
               WHEN 'ctVIDEO' THEN AV.common_name 
               WHEN 'ctSEASON' THEN ASN.common_name 
               WHEN 'ctCOLLECTION' THEN ACN.common_name 
               WHEN 'ctGIF' THEN AG.common_name
               WHEN 'ctTEXT' THEN AT.common_name
               ELSE '' 
            END",
            'name' => "String:CASE ctype
                WHEN 'ctVIDEO' THEN COALESCE(AVL1.name,AVL2.name)
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)
                WHEN 'ctCOLLECTION' THEN COALESCE(ACL1.name,ACL2.name)
                WHEN 'ctGIF' THEN COALESCE(AGS1.name,AGS2.name)
                WHEN 'ctTEXT' THEN COALESCE(ATS1.name,ATS2.name)
                ELSE ''
            END",
            'mcsort' => 'Int:A.mcsort',
        ];
    }

    protected function get_sort_separator(): string {
        return "|";
    }

    protected function get_sorts(): array {
        return [
            'id' => 'A.id',
            'common_name' => "CASE ctype
               WHEN 'ctVIDEO' THEN AV.common_name 
               WHEN 'ctSEASON' THEN ASN.common_name 
               WHEN 'ctCOLLECTION' THEN ACN.common_name 
               WHEN 'ctGIF' THEN AG.common_name
               WHEN 'ctTEXT' THEN AT.common_name
               ELSE '' 
            END",
            'name' => "CASE ctype
                WHEN 'ctVIDEO' THEN COALESCE(AVL1.name,AVL2.name)
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)
                WHEN 'ctCOLLECTION' THEN COALESCE(ACL1.name,ACL2.name)
                WHEN 'ctGIF' THEN COALESCE(AGS1.name,AGS2.name)
                WHEN 'ctTEXT' THEN COALESCE(ATS1.name,ATS2.name)
                ELSE ''
            END",
            'mcsort' => 'A.mcsort|A.id',
        ];
    }

    protected function create_direct_conditions() {
        $this->filter->addDirectCondition("(A.ctype='ctVIDEO' OR A.ctype='ctSEASON' OR A.ctype='ctCOLLECTION' OR A.ctype='ctGIF' OR A.ctype='ctTEXT')"); //OR ....
    }

    protected function build_query() {
        $language = \Language\LanguageList::F()->get_current_language();
        $def_language = \Language\LanguageList::F()->get_default_language();
        $q = sprintf("
            SELECT SQL_CALC_FOUND_ROWS
            A.id,A.ctype,A.enabled,A.mcsort,                        
            CASE ctype
                WHEN 'ctVIDEO' THEN COALESCE(AVL1.name,AVL2.name)
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)
                WHEN 'ctCOLLECTION' THEN COALESCE(ACL1.name,ACL2.name)
                WHEN 'ctGIF' THEN COALESCE(AGS1.name,AGS2.name)
                WHEN 'ctTEXT' THEN COALESCE(ATS1.name,ATS2.name)
                ELSE ''
            END name,
            CASE ctype
                WHEN 'ctVIDEO' THEN AV.vertical
                ELSE 0 
            END vertical,
            CASE ctype
               WHEN 'ctVIDEO' THEN AV.common_name 
               WHEN 'ctSEASON' THEN ASN.common_name 
               WHEN 'ctCOLLECTION' THEN ACN.common_name 
               WHEN 'ctGIF' THEN AG.common_name
               WHEN 'ctTEXT' THEN AT.common_name
               ELSE '' 
            END common_name
            FROM media__content A 
            LEFT JOIN media__content__video AV ON(AV.id=A.id)
            LEFT JOIN media__content__video__strings__lang_%s AVL1 ON(AVL1.id=A.id)
            LEFT JOIN media__content__video__strings__lang_%s AVL2 ON(AVL2.id=A.id)
            LEFT JOIN media__content__season ASN ON(ASN.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL1 ON(ASL1.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL2 ON(ASL2.id=A.id)
            LEFT JOIN media__content__collection ACN ON(ACN.id=A.id)
            LEFT JOIN media__content__collection_strings_lang_%s ACL1 ON(ACL1.id=A.id)
            LEFT JOIN media__content__collection_strings_lang_%s ACL2 ON(ACL2.id=A.id)
            LEFT JOIN media__content__gif AG ON(AG.id=A.id)
            LEFT JOIN media__content__gif__strings AGS1 ON(AGS1.id=A.id AND AGS1.language_id='%s')
            LEFT JOIN media__content__gif__strings AGS2 ON(AGS2.id=A.id AND AGS2.language_id='%s')
            LEFT JOIN media__content__text AT ON(AT.id=A.id)
            LEFT JOIN media__content__text__strings__lang_%s ATS1 ON(ATS1.id=A.id)
            LEFT JOIN media__content__text__strings__lang_%s ATS2 ON(ATS2.id=A.id)
            %s %s %s %s            
            ",
                $language, $def_language, $language, $def_language,
                $language, $def_language,
                $language, $def_language,
                $language, $def_language,
                $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);

        \Out\Out::F()->add('lister_debug', $q);
        return $q;
    }

}
