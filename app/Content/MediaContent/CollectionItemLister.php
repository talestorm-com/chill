<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent;

/**
 * Description of CollectionItemLister
 *
 * @author eve
 */
class CollectionItemLister extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'named_common_name' => "String:CASE ctype
               WHEN 'ctVIDEO' THEN AV.common_name 
               WHEN 'ctSEASON' THEN ASN.common_name 
               ELSE '' 
            END",
            'named_name' => "String:CASE ctype
                WHEN 'ctVIDEO' THEN COALESCE(AVL1.name,AVL2.name)
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)
                ELSE ''
            END",
            'enabled'=>'Int:A.enabled',
        ];
    }

    protected function get_sort_separator(): string {
        return "|";
    }

    protected function get_sorts(): array {
        return [
            'id' => 'A.id',
            'named_common_name' => "CASE ctype
               WHEN 'ctVIDEO' THEN AV.common_name 
               WHEN 'ctSEASON' THEN ASN.common_name                
               ELSE '' 
            END|A.id",
            'named_name' => "CASE ctype
                WHEN 'ctVIDEO' THEN COALESCE(AVL1.name,AVL2.name)
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)
                ELSE ''
            END|A.id",
            'enabled'=>'A.enabled|A.id',
        ];
    }

    protected function create_direct_conditions() {
        $this->filter->addDirectCondition("(A.ctype='ctVIDEO' OR A.ctype='ctSEASON')"); //OR ....
    }

    protected function build_query() {
        $language = \Language\LanguageList::F()->get_current_language();
        $def_language = \Language\LanguageList::F()->get_default_language();
        return sprintf("
            SELECT SQL_CALC_FOUND_ROWS
            A.id,A.ctype,A.enabled,                        
            CASE ctype
                WHEN 'ctVIDEO' THEN COALESCE(AVL1.name,AVL2.name)
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)
                ELSE ''
            END name,            
            CASE ctype
               WHEN 'ctVIDEO' THEN AV.common_name 
               WHEN 'ctSEASON' THEN ASN.common_name 
               ELSE '' 
            END common_name
            FROM media__content A 
            LEFT JOIN media__content__video AV ON(AV.id=A.id)
            LEFT JOIN media__content__video__strings__lang_%s AVL1 ON(AVL1.id=A.id)
            LEFT JOIN media__content__video__strings__lang_%s AVL2 ON(AVL2.id=A.id)
            LEFT JOIN media__content__season ASN ON(ASN.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL1 ON(ASL1.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL2 ON(ASL2.id=A.id)            
            
            %s %s %s %s            
            ",
                $language, $def_language, $language, $def_language,
                $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
