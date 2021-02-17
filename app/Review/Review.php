<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Review;

/**
 * Description of Review
 *
 * @author eve
 * @property int $media_id
 * @property int $user_id
 * @property string $name
 * @property string $user_name
 * @property string $login
 * @property bool $approved
 * @property string $info
 * @property int $rate
 */
class Review implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;
    //<editor-fold defaultstate="collapsed" desc="fields">

    /** @var int */
    protected $media_id;

    /** @var int */
    protected $user_id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $user_name;

    /** @var string */
    protected $login;

    /** @var bool */
    protected $approved;

    /** @var string */
    protected $info;
    /** @var int */
protected $rate;


    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__media_id() {
        return $this->media_id;
    }

    /** @return int */
    protected function __get__user_id() {
        return $this->user_id;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__user_name() {
        return $this->user_name;
    }

    /** @return string */
    protected function __get__login() {
        return $this->login;
    }

    /** @return bool */
    protected function __get__approved() {
        return $this->approved;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return bool */
    protected function __get__valid() {
        return true;
    }
    /** @return int */
protected function __get__rate(){return $this->rate;}


    //</editor-fold>

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    /**
     * 
     * @param int $media_id
     * @param int $user_id
     * @return $this
     */
    public function load(int $media_id, int $user_id) {
        $query = "
            SELECT A.media_id,A.user_id,A.rate,A.info,A.approved,
            UF.name user_name,U.login,
            B.ctype content_type,DATE_FORMAT(A.post,'%%d.%%m.%%Y %%H:%%i') post,
            CASE B.ctype
                WHEN 'ctVIDEO' THEN COALESCE(AVL1.name,AVL2.name)
                WHEN 'ctSEASON' THEN COALESCE(ASL1.name,ASL2.name)
                WHEN 'ctCOLLECTION' THEN COALESCE(ACL1.name,ACL2.name)
                WHEN 'ctGIF' THEN COALESCE(AGS1.name,AGS2.name)
                WHEN 'ctTEXT' THEN COALESCE(ATS1.name,ATS2.name)
                ELSE ''
            END name
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
            WHERE A.media_id=:M AND A.user_id=:U ;            
            ";
        $lang = \Language\LanguageList::F()->get_current_language();
        $dlang = \Language\LanguageList::F()->get_default_language();
        $row = \DB\DB::F()->queryRow(sprintf($query, $lang, $dlang, $lang, $dlang, $lang, $dlang, $lang, $dlang, $lang, $dlang), [":M" => $media_id, ":U" => $user_id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
        return $this;
    }

    protected function t_common_import_get_filters() {
        return [
            'media_id' => ['IntMore0'], //int
            'user_id' => ['IntMore0'], //int
            'rate' => ['IntMore0','Default0'], //int
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'user_name' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'login' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'approved' => ['Boolean', 'DefaultFalse'], //bool
            'info' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
        ];
    }

}
