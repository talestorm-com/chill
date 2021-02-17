<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Readers\ctVIDEO;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property int $id
 * @property string $common_name
 * @property string $name
 * @property int $year
 * @property Float $price
 * @property String $cdn_id
 * @property Boolean $vertical
 * @property Boolean $enabled
 * @property \Content\MediaContent\TagList\TagList $genres
 * @property \Content\MediaContent\TagList\TagList $countries
 * @property \Content\MediaContent\TagList\TagList $studios
 * @property \Content\MediaContent\TagList\TagList $tags
 * @property int $emoji
 * @property string $emoji_name
 * @property int $age_restriction
 * @property string $age_restriction_name
 * @property int $html_mode
 * @property string $intro
 * @property string $info
 * @property string $default_frame
 * @property string $default_poster
 * @property string $meta_title
 * @property string $og_title
 * @property string $meta_keywords
 * @property String $meta_description
 * @property String $og_description
 * @property Properties $properties
 * @property PersonalList $personal
 * @property String $content_type  
 * @property \Content\MediaContent\FileList\FileList $files
 */
class MediaContentObject implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    CONST MEDIA_CONTEXT_FRAMES = "media_content_frame";
    CONST MEDIA_CONTEXT_POSTERS = "media_content_poster";

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $common_name;

    /** @var string */
    protected $name;

    /** @var int */
    protected $year;

    /** @var Float */
    protected $price;

    /** @var String */
    protected $cdn_id;

    /** @var Boolean */
    protected $vertical;

    /** @var Boolean */
    protected $enabled;

    /** @var \Content\MediaContent\TagList\TagList */
    protected $genres;

    /** @var \Content\MediaContent\TagList\TagList */
    protected $countries;
    /** @var \Content\MediaContent\TagList\TagList */
    protected $tags;

    /** @var \Content\MediaContent\TagList\TagList */
    protected $studios;

    /** @var int */
    protected $emoji;

    /** @var int */
    protected $emoji_name;

    /** @var int */
    protected $age_restriction;

    /** @var int */
    protected $age_restriction_name;

    /** @var int */
    protected $html_mode;

    /** @var string */
    protected $intro;

    /** @var string */
    protected $info;

    /** @var string */
    protected $default_frame;

    /** @var string */
    protected $default_poster;

    /** @var string */
    protected $meta_title;

    /** @var string */
    protected $og_title;

    /** @var string */
    protected $meta_keywords;

    /** @var String */
    protected $meta_description;

    /** @var String */
    protected $og_description;

    /** @var Properties */
    protected $properties;

    /** @var PersonalList */
    protected $personal;

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

    /** @return string */
    protected function __get__common_name() {
        return $this->common_name;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return int */
    protected function __get__year() {
        return $this->year;
    }

    /** @return Float */
    protected function __get__price() {
        return $this->price;
    }

    /** @return String */
    protected function __get__cdn_id() {
        return $this->cdn_id;
    }

    /** @return Boolean */
    protected function __get__vertical() {
        return $this->vertical;
    }

    /** @return Boolean */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return \Content\MediaContent\TagList\TagList */
    protected function __get__genres() {
        return $this->genres;
    }
    /** @return \Content\MediaContent\TagList\TagList */
    protected function __get__tags() {
        return $this->tags;
    }

    /** @return \Content\MediaContent\TagList\TagList */
    protected function __get__countries() {
        return $this->countries;
    }

    /** @return \Content\MediaContent\TagList\TagList */
    protected function __get__studios() {
        return $this->studios;
    }

    /** @return int */
    protected function __get__emoji() {
        return $this->emoji;
    }

    /** @return int */
    protected function __get__emoji_name() {
        return $this->emoji_name;
    }

    /** @return int */
    protected function __get__age_restriction() {
        return $this->age_restriction;
    }

    /** @return int */
    protected function __get__age_restriction_name() {
        return $this->age_restriction_name;
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
    protected function __get__default_frame() {
        return $this->default_frame;
    }

    /** @return string */
    protected function __get__default_poster() {
        return $this->default_poster;
    }

    /** @return string */
    protected function __get__meta_title() {
        return $this->meta_title;
    }

    /** @return string */
    protected function __get__og_title() {
        return $this->og_title;
    }

    /** @return string */
    protected function __get__meta_keywords() {
        return $this->meta_keywords;
    }

    /** @return String */
    protected function __get__meta_description() {
        return $this->meta_description;
    }

    /** @return String */
    protected function __get__og_description() {
        return $this->og_description;
    }

    /** @return Properties */
    protected function __get__properties() {
        return $this->properties;
    }

    /** @return PersonalList */
    protected function __get__personal() {
        return $this->personal;
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
            A.id,A.ctype,A.enabled,A.age_restriction,AGR.international_name age_restriction_name,
            A.emoji,EMJ.tag emoji_name,B.common_name,B.vertical,B.cdn_id,B.year,
            COALESCE(P.price,0)price,
            COALESCE(LV1.name,LV2.name)name,
            COALESCE(LV1.html_mode,LV2.html_mode)html_mode,
            COALESCE(LV1.intro,LV2.intro) intro,
            COALESCE(LV1.info,LV2.info) info,
            COALESCE(MV1.title,MV2.title) meta_title,
            COALESCE(MV1.og_title,MV2.og_title) og_title,
            COALESCE(MV1.description,MV2.description) meta_description,
            COALESCE(MV1.og_description,MV2.og_description) og_description,
            COALESCE(MV1.keywords,MV2.keywords) meta_keywords,
            B.default_poster,B.default_frame
        
            FROM media__content A JOIN media__content__video B ON (A.id=B.id)
            LEFT JOIN media__content__video__strings__lang_%s LV1 ON (LV1.id=A.id)
            LEFT JOIN media__content__video__strings__lang_%s LV2 ON (LV2.id=A.id)
            LEFT JOIN media__content__meta_lang_%s MV1 ON (MV1.id=A.id)
            LEFT JOIN media__content__meta_lang_%s MV2 ON (MV2.id=A.id)
            LEFT JOIN media__emoji EMJ ON(EMJ.id=A.emoji)
            LEFT JOIN media__age__restriction AGR ON (AGR.id=A.age_restriction)
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
            'common_name' => ['Strip', 'Trim', 'NEString'], //string
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'year' => ['IntMore0', 'DefaultNull'], //int
            'price' => ['Float', 'DefaultNull'], //Float
            'cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //String
            'vertical' => ['Boolean', 'DefaultFalse'], //Boolean
            'enabled' => ['Boolean', 'DefaultFalse'], //Boolean
            'emoji' => ['IntMore0', 'DefaultNull'], //int
            'emoji_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //int
            'age_restriction' => ['IntMore0', 'DefaultNull'], //int
            'age_restriction_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //int
            'html_mode' => ['IntMore0', 'Default0'], //int
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'default_frame' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'meta_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'og_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'meta_keywords' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'meta_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //String
            'og_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //String            
            'content_type' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //String
        ];
    }

    protected function t_common_import_after_import() {
        $this->content_type = 'ctVIDEO';
        $this->properties = \Content\MediaContent\Properties::F()->load_from_database($this->id);
        $this->personal = \Content\MediaContent\PersonalList::F()->load($this->id);
        $this->countries = \Content\MediaContent\TagList\CountryTagList::F($this->id);
        $this->genres = \Content\MediaContent\TagList\GenreTagList::F($this->id);
        $this->tags = \Content\MediaContent\TagList\TagTagList::F($this->id);
        $this->studios = \Content\MediaContent\TagList\StudioTagList::F($this->id);
        $this->files = \Content\MediaContent\FileList\VideoFileList::F($this->id);
    }

}
