<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Readers\ctSEASONSEASON;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property int $id
 * @property int $season_id
 * @property int $num
 * @property string $name
 * @property string $common_name
 * @property Boolean $enabled
 * @property int $html_mode
 * @property string $intro
 * @property string $info
 * @property string $default_poster
 * @property String $content_type   
 */
class MediaContentObject implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    CONST MEDIA_CONTEXT_POSTERS = "media_content_poster";

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var int */
    protected $season_id;

    /** @var int */
    protected $num;

    /** @var string */
    protected $name;

    /** @var string */
    protected $common_name;

    /** @var Boolean */
    protected $enabled;

    /** @var int */
    protected $html_mode;

    /** @var string */
    protected $intro;

    /** @var string */
    protected $info;

    /** @var string */
    protected $default_poster;

    /** @var String */
    protected $content_type;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__season_id() {
        return $this->season_id;
    }

    /** @return int */
    protected function __get__num() {
        return $this->num;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__common_name() {
        return $this->common_name;
    }

    /** @return Boolean */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return int */
    protected function __get__html_mode() {
        return $this->html_mode;
    }

    /** @return string */
    protected function __get__intro() {
        return $this->intro;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return string */
    protected function __get__default_poster() {
        return $this->default_poster;
    }

    /** @return String */
    protected function __get__content_type() {
        return $this->content_type;
    }

    //</editor-fold>

    protected function __construct(int $id) {
        $this->load($id);
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id) {
        return new static($id);
    }

    protected function load(int $id) {
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $query = "
            SELECT 
            A.id,A.ctype,A.enabled,
            B.season_id,B.num,B.common_name,
            COALESCE(LV1.name,LV2.name)name,
            COALESCE(LV1.html_mode,LV2.html_mode)html_mode,
            COALESCE(LV1.intro,LV2.intro) intro,
            COALESCE(LV1.info,LV2.info) info,
            B.default_poster
        
            FROM media__content A JOIN media__content__season__season B ON (A.id=B.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s LV1 ON (LV1.id=A.id)
            LEFT JOIN media__content__seasonseason__strings__lang_%s LV2 ON (LV2.id=A.id)            
            WHERE A.id=:P
            ";

        $row = \DB\DB::F()->queryRow(sprintf($query, $language, $default_language, $language, $default_language), [":P" => $id]);

        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'season_id' => ['IntMore0',], //string
            'num' => ['IntMore0',], //string
            'common_name' => ['Strip', 'Trim', 'NEString','DefaultEmptyString'], //string
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'enabled' => ['Boolean', 'DefaultFalse'], //Boolean
            'html_mode' => ['IntMore0', 'Default0'], //int
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string            
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'content_type' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //String
        ];
    }

    protected function t_common_import_after_import() {
        $this->content_type = 'ctSEASONSEASON';
    }

}
