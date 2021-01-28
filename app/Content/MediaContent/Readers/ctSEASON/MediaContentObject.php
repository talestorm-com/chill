<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Readers\ctSEASON;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property int $id
 * @property string $common_name
 * @property string $eng_name
 * @property string $name
 * @property Boolean $enabled
 * @property Boolean $free
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
 * @property string $default_poster
 * @property string $meta_title
 * @property string $og_title
 * @property string $meta_keywords
 * @property String $meta_description
 * @property String $og_description
 * @property Properties $properties
 * @property PersonalList $personal
 * @property String $content_type   
 * @property \DateTime $released
 * @property string $origin_language
 * @property int $series_count
 * @property int $seasons_count
 * @property int $track_language
 * @property string $track_language_name
 * @property int $mcsort
 * @property string $preplay_name
 * @property int $preplay
 * @property string $video_cdn_id
 * @property string $video_cdn_url
 * @property string $lent_mode
 * @property string $lent_image_name
 * @property string $gif_cdn_id
 * @property string $gif_cdn_url
 * @property string $lent_message
 */
class MediaContentObject implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    CONST MEDIA_CONTEXT_POSTERS = "media_content_poster";
    CONST MEDIA_CONTEXT_FRAMES = "media_content_frame";
    CONST MEDIA_CONTEXT_PREVIEW = "media_content_preview";

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $common_name;

    /** @var string */
    protected $name;

    /** @var string */
    protected $eng_name;

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

    /** @var \DateTime */
    protected $released;

    /** @var string */
    protected $origin_language;

    /** @var int */
    protected $series_count;

    /** @var int */
    protected $seasons_count;

    /** @var int */
    protected $track_language;

    /** @var string */
    protected $track_language_name;

    /** @var Boolean */
    protected $free;

    /** @var int */
    protected $mcsort;

    /** @var string */
    protected $preplay_name;

    /** @var int */
    protected $preplay;

    /** @var string */
    protected $video_cdn_id;

    /** @var string */
    protected $video_cdn_url;

    /** @var string */
    protected $lent_mode;

    /** @var string */
    protected $lent_image_name;

    /** @var string */
    protected $gif_cdn_id;

    /** @var string */
    protected $gif_cdn_url;

    /** @var string */
    protected $lent_message;

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
    protected function __get__eng_name() {
        return $this->eng_name;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
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
    protected function __get__countries() {
        return $this->countries;
    }

    /** @return \Content\MediaContent\TagList\TagList */
    protected function __get__tags() {
        return $this->tags;
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

    /** @return \DateTime */
    protected function __get__released() {
        return $this->released;
    }

    /** @return string */
    protected function __get__origin_language() {
        return $this->origin_language;
    }

    /** @return int */
    protected function __get__series_count() {
        return $this->series_count;
    }

    /** @return int */
    protected function __get__seasons_count() {
        return $this->seasons_count;
    }

    /** @return int */
    protected function __get__track_language() {
        return $this->track_language;
    }

    /** @return string */
    protected function __get__track_language_name() {
        return $this->track_language_name;
    }

    /** @return Boolean */
    protected function __get__free() {
        return $this->free;
    }

    /** @return int */
    protected function __get__mcsort() {
        return $this->mcsort;
    }

    /** @return string */
    protected function __get__preplay_name() {
        return $this->preplay_name;
    }

    /** @return int */
    protected function __get__preplay() {
        return $this->preplay;
    }

    /** @return string */
    protected function __get__video_cdn_id() {
        return $this->video_cdn_id;
    }

    /** @return string */
    protected function __get__video_cdn_url() {
        return $this->video_cdn_url;
    }

    /** @return string */
    protected function __get__lent_mode() {
        return $this->lent_mode;
    }

    /** @return string */
    protected function __get__lent_image_name() {
        return $this->lent_image_name;
    }

    /** @return string */
    protected function __get__gif_cdn_id() {
        return $this->gif_cdn_id;
    }

    /** @return string */
    protected function __get__gif_cdn_url() {
        return $this->gif_cdn_url;
    }

    /** @return string */
    protected function __get__lent_message() {
        return $this->lent_message;
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
            A.id,A.ctype,A.enabled,A.age_restriction,AGR.international_name age_restriction_name,A.mcsort,
            A.emoji,EMJ.tag emoji_name,B.common_name,B.eng_name,
            A.track_language,A.series_count,A.seasons_count,COALESCE(TL1.name,TL2.name)track_language_name,A.free,
            COALESCE(LV1.name,LV2.name)name,
            COALESCE(LV1.html_mode,LV2.html_mode)html_mode,
            COALESCE(LV1.intro,LV2.intro) intro,
            COALESCE(LV1.info,LV2.info) info,
            COALESCE(MV1.title,MV2.title) meta_title,
            COALESCE(MV1.og_title,MV2.og_title) og_title,
            COALESCE(MV1.description,MV2.description) meta_description,
            COALESCE(MV1.og_description,MV2.og_description) og_description,
            COALESCE(MV1.keywords,MV2.keywords) meta_keywords,
            B.default_poster,B.released,B.origin_language,
            MPV.id preplay, MPV.name preplay_name,
            MLV.cdn_id video_cdn_id,MLV.cdn_url video_cdn_url,MLM.mode lent_mode,
            MLM.message lent_message,MLM.lent_image_name,
            MLG.cdn_id gif_cdn_id,MLG.cdn_url gif_cdn_url,
            

            NULL sys_dummy_val
            FROM media__content A JOIN media__content__season B ON (A.id=B.id)
            LEFT JOIN media__content__season__strings__lang_%s LV1 ON (LV1.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s LV2 ON (LV2.id=A.id)
            LEFT JOIN media__content__meta_lang_%s MV1 ON (MV1.id=A.id)
            LEFT JOIN media__content__meta_lang_%s MV2 ON (MV2.id=A.id)
            LEFT JOIN media__emoji EMJ ON(EMJ.id=A.emoji)
            LEFT JOIN media__age__restriction AGR ON (AGR.id=A.age_restriction)   
            LEFT JOIN media__content__tracklang__strings TL1 ON(TL1.id=A.track_language AND TL1.language_id='%s')
            LEFT JOIN media__content__tracklang__strings TL2 ON(TL2.id=A.track_language AND TL2.language_id='%s')
            LEFT JOIN media__content__preplay MCPP ON(MCPP.content_id=A.id)
            LEFT JOIN media__preplay__video MPV ON(MPV.id=MCPP.preplay_id)
            LEFT JOIN media__lent__mode MLM ON(MLM.id=A.id)
            LEFT JOIN media__lent__gif MLG ON(MLG.id=A.id)
            LEFT JOIN media__lent__video MLV ON(MLV.id=A.id)
            WHERE A.id=:P
            ";

        $row = \DB\DB::F()->queryRow(sprintf($query, $language, $default_language, $language, $default_language, $language, $default_language), [":P" => $id]);

        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'common_name' => ['Strip', 'Trim', 'NEString'], //string
            'eng_name' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'enabled' => ['Boolean', 'DefaultFalse'], //Boolean
            'free' => ['Boolean', 'DefaultFalse'], //Boolean
            'emoji' => ['IntMore0', 'DefaultNull'], //int
            'emoji_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //int
            'age_restriction' => ['IntMore0', 'DefaultNull'], //int
            'age_restriction_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //int
            'html_mode' => ['IntMore0', 'Default0'], //int
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string            
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'meta_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'og_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'meta_keywords' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'meta_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //String
            'og_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //String   
            'released' => ['DateMatch', 'DefaultNull'],
            'origin_language' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'content_type' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //String
            'series_count' => ['IntMore0', 'DefaultNull'], //int
            'seasons_count' => ['IntMore0', 'DefaultNull'], //int            
            'track_language' => ['IntMore0', 'DefaultNull'], //int
            'mcsort' => ['Int', 'Default0'], //int
            'track_language_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'preplay_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'preplay' => ['IntMore0', 'DefaultNull'],
            'video_cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'video_cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'lent_mode' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'lent_image_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'gif_cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'gif_cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'lent_message' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

    protected function t_common_import_after_import() {
        $this->content_type = 'ctSEASON';
        $this->properties = \Content\MediaContent\Properties::F()->load_from_database($this->id);
        $this->personal = \Content\MediaContent\PersonalList::F()->load($this->id);
        $this->countries = \Content\MediaContent\TagList\CountryTagList::F($this->id);
        $this->genres = \Content\MediaContent\TagList\GenreTagList::F($this->id);
        $this->studios = \Content\MediaContent\TagList\StudioTagList::F($this->id);
        $this->tags = \Content\MediaContent\TagList\TagTagList::F($this->id);
        $this->lent_mode ? 0 : $this->lent_mode = 'poster';
    }

    protected function t_default_marshaller_export_property_released() {
        return $this->released ? $this->released->format('d.m.Y H:i') : null;
    }

}
