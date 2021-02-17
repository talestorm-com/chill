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
class listerSOAP extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'common_name' => "String:CASE ctype
               WHEN 'ctSEASON' THEN ASN.common_name                
               ELSE '' 
            END",
            'name' => "String:CASE ctype                
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)                
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
               WHEN 'ctSEASON' THEN ASN.common_name                
               ELSE '' 
            END",
            'name' => "CASE ctype                
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)                
                ELSE ''
            END",
            'mcsort' => 'A.mcsort|A.id',
        ];
    }

    protected function create_direct_conditions() {
        $this->filter->addDirectCondition("(A.ctype='ctSEASON' AND A.enabled=1)"); //OR ....
    }

    protected function build_query() {
        $language = \Language\LanguageList::F()->get_current_language();
        $def_language = \Language\LanguageList::F()->get_default_language();
        $q = sprintf("
            SELECT SQL_CALC_FOUND_ROWS
            A.id,A.ctype,A.enabled,A.mcsort,                        
            CASE ctype                
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)                
                ELSE ''
            END name,
            0 vertical,
            CASE ctype             
               WHEN 'ctSEASON' THEN ASN.common_name                
               ELSE '' 
            END common_name,
            COALESCE(G.position,-1) `grid`
            FROM media__content A                         
            LEFT JOIN media__content__season ASN ON(ASN.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL1 ON(ASL1.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL2 ON(ASL2.id=A.id)    
            LEFT JOIN media__grid G ON(G.content_id=A.id)
            %s %s %s %s            
            ",
                $language, $def_language, 
                $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);

        \Out\Out::F()->add('lister_debug', $q);
        return $q;
    }

}
