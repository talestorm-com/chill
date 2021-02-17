<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Review;

/**
 * Description of Lister
 *
 * @author eve
 */
class Lister extends \Content\Lister\Lister {

    protected function get_sort_separator(): string {
        return '|';
    }

    protected function get_filters(): array {
        return[
            'media_id' => 'Int:A.media_id',
            'user_id' => 'Int:A.user_id',
            'approved' => 'Int:A.approved',
            'name' => "String:CASE B.ctype
                WHEN 'ctVIDEO' THEN COALESCE(AVL1.name,AVL2.name)
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)
                WHEN 'ctCOLLECTION' THEN COALESCE(ACL1.name,ACL2.name)
                WHEN 'ctGIF' THEN COALESCE(AGS1.name,AGS2.name)
                WHEN 'ctTEXT' THEN COALESCE(ATS1.name,ATS2.name)
                ELSE ''
            END",
            'user_name' => 'String:UF.name',
            'login' => 'String:U.login',
            'post'=>'Date:A.post'
        ];
    }

    protected function get_sorts(): array {
        return [
            'media_id' => 'A.approved ASC|A.media_id|A.user_id',
            'user_id' => 'A.approved ASC|A.user_id|A.media_id',
            'name' => "A.approved ASC|CASE B.ctype
                WHEN 'ctVIDEO' THEN COALESCE(AVL1.name,AVL2.name)
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)
                WHEN 'ctCOLLECTION' THEN COALESCE(ACL1.name,ACL2.name)
                WHEN 'ctGIF' THEN COALESCE(AGS1.name,AGS2.name)
                WHEN 'ctTEXT' THEN COALESCE(ATS1.name,ATS2.name)
                ELSE ''
            END|A.user_id|A.media_id",
            'user_name' => 'A.approved ASC|UF.name|A.user_id|A.media_id',
            'login' => 'A.approved ASC|U.login|A.media_id',
            'post' => 'A.approved ASC|A.post|A.media_id|A.user_id',
        ];
    }

    protected function build_query() {
        $lang = \Language\LanguageList::F()->get_current_language();
        $dl = \Language\LanguageList::F()->get_default_language();
        $query_proto = "
            SELECT A.media_id,A.user_id,A.rate,SUBSTRING(A.info, 1, 50) info,A.approved,
            UF.name user_name,U.login,
            B.ctype content_type,DATE_FORMAT(A.post,'%%d.%%m.%%Y %%H:%%i') post,
            CASE B.ctype
                WHEN 'ctVIDEO' THEN COALESCE(AVL1.name,AVL2.name)
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)
                WHEN 'ctCOLLECTION' THEN COALESCE(ACL1.name,ACL2.name)
                WHEN 'ctGIF' THEN COALESCE(AGS1.name,AGS2.name)
                WHEN 'ctTEXT' THEN COALESCE(ATS1.name,ATS2.name)
                ELSE ''
            END name,
            null dmy        
            FROM media__content__review A JOIN user U ON(U.id=A.user_id)
            JOIN user__fields UF ON(UF.id=A.user_id)
            JOIN media__content B ON(B.id=A.media_id)
            LEFT JOIN media__content__video AV ON(AV.id=B.id)
            LEFT JOIN media__content__video__strings__lang_%s AVL1 ON(AVL1.id=B.id)
            LEFT JOIN media__content__video__strings__lang_%s AVL2 ON(AVL2.id=B.id)
            LEFT JOIN media__content__season ASN ON(ASN.id=B.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL1 ON(ASL1.id=B.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL2 ON(ASL2.id=B.id)
            LEFT JOIN media__content__collection ACN ON(ACN.id=B.id)
            LEFT JOIN media__content__collection_strings_lang_%s ACL1 ON(ACL1.id=B.id)
            LEFT JOIN media__content__collection_strings_lang_%s ACL2 ON(ACL2.id=B.id)
            LEFT JOIN media__content__gif AG ON(AG.id=B.id)
            LEFT JOIN media__content__gif__strings AGS1 ON(AGS1.id=B.id AND AGS1.language_id='%s')
            LEFT JOIN media__content__gif__strings AGS2 ON(AGS1.id=B.id AND AGS2.language_id='%s')
            LEFT JOIN media__content__text AT ON(AT.id=B.id)
            LEFT JOIN media__content__text__strings__lang_%s ATS1 ON(ATS1.id=B.id)
            LEFT JOIN media__content__text__strings__lang_%s ATS2 ON(ATS2.id=B.id)
            
            %s %s %s %s                                   
            ";
        return sprintf($query_proto, $lang, $dl, $lang, $dl, $lang, $dl, $lang, $dl, $lang, $dl, $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
