<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Readers\ctSEASONSERIES;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property int $id
 * @property int $seasonseason_id
 * @property string $common_name
 * @property string $name 
 * @property Float $price 
 * @property Boolean $vertical
 * @property Boolean $enabled
 * @property int $html_mode
 * @property string $intro
 * @property string $info
 * @property string $default_poster
 * @property string $content_type
 * @property \Content\MediaContent\FileList\FileList $files
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
    protected $seasonseason_id;
    /** @var int */
    protected $num;

    /** @var string */
    protected $common_name;

    /** @var string */
    protected $name;

    /** @var Float */
    protected $price;

    /** @var Boolean */
    protected $vertical;

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

    /** @var \Content\MediaContent\FileList\FileList */
    protected $files;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__seasonseason_id() {
        return $this->seasonseason_id;
    }
    /** @return int */
    protected function __get__num() {
        return $this->num;
    }

    /** @return string */
    protected function __get__common_name() {
        return $this->common_name;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return Float */
    protected function __get__price() {
        return $this->price;
    }

    /** @return Boolean */
    protected function __get__vertical() {
        return $this->vertical;
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

    /** @return \Content\MediaContent\FileList\FileList */
    protected function __get__files() {
        return $this->files;
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
            A.id,A.ctype,A.enabled,B.common_name,B.vertical,B.seasonseason_id,
            COALESCE(P.price,0)price,
            COALESCE(LV1.name,LV2.name)name,
            COALESCE(LV1.html_mode,LV2.html_mode)html_mode,
            COALESCE(LV1.intro,LV2.intro) intro,
            COALESCE(LV1.info,LV2.info) info,       
            B.num,
            B.default_poster
        
            FROM media__content A JOIN media__content__season__series B ON (A.id=B.id)
            LEFT JOIN media__content_seasonseries_strings__lang_%s LV1 ON (LV1.id=A.id)
            LEFT JOIN media__content_seasonseries_strings__lang_%s LV2 ON (LV2.id=A.id)            
            LEFT JOIN media__content__price P ON(P.id=A.id)
            WHERE A.id=:P
            ";

        $row = \DB\DB::F()->queryRow(sprintf($query, $language, $default_language, $language, $default_language), [":P" => $id]);

        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'seasonseason_id' => ['IntMore0'], //int
            'num' => ['IntMore0'], //int
            'common_name' => ['Strip', 'Trim', 'NEString'], //string
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'price' => ['Float', 'DefaultNull'], //Float
            'vertical' => ['Boolean', 'DefaultFalse'], //Boolean
            'enabled' => ['Boolean', 'DefaultFalse'], //Boolean
            'html_mode' => ['IntMore0', 'Default0'], //int
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string            
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string            
            'content_type' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //String
        ];
    }

    protected function t_common_import_after_import() {
        $this->content_type = 'ctSEASONSERIES';
        $this->files = \Content\MediaContent\FileList\VideoFileList::F($this->id);
    }

}
