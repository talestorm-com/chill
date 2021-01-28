<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Readers\ctGIF;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property int $id
 * @property string $common_name
 * @property string $default_poster
 * @property bool $enabled
 * @property bool $free
 * @property array $strings
 * @property string $cdn_id
 * @property string $cdn_url
 * @property \Content\MediaContent\TagList\TagList $tags
 * @property string $target
 * @property int $series_count
 * @property int $seasons_count
 * @property int $track_language
 * @property string $track_language_name
 * @property \Content\MediaContent\TagList\GenreTagList $genres
 * @property \Content\MediaContent\TagList\CountryTagList $countries
 */
class MediaContentObject implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    const MEDIA_CONTEXT = "media_content_poster";

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $common_name;

    /** @var string */
    protected $default_poster;

    /** @var bool */
    protected $enabled;

    /** @var array */
    protected $strings;

    /** @var string */
    protected $cdn_id;

    /** @var string */
    protected $cdn_url;

    /** @var \Content\MediaContent\TagList\TagList */
    protected $tags;

    /** @var string */
    protected $target;

    /** @var int */
    protected $series_count;

    /** @var int */
    protected $seasons_count;

    /** @var int */
    protected $track_language;

    /** @var string */
    protected $track_language_name;

    /** @var \Content\MediaContent\TagList\GenreTagList */
    protected $genres;

    /** @var \Content\MediaContent\TagList\CountryTagList */
    protected $countries;
    /** @var bool */
protected $free;


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
    protected function __get__default_poster() {
        return $this->default_poster;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return array */
    protected function __get__strings() {
        return $this->strings;
    }

    /** @return string */
    protected function __get__cdn_id() {
        return $this->cdn_id;
    }

    /** @return string */
    protected function __get__cdn_url() {
        return $this->cdn_url;
    }

    /** @return \Content\MediaContent\TagList\TagList */
    protected function __get__tags() {
        return $this->tags;
    }

    /** @return string */
    protected function __get__target() {
        return $this->target;
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

    /** @return \Content\MediaContent\TagList\GenreTagList */
    protected function __get__genres() {
        return $this->genres;
    }

    /** @return \Content\MediaContent\TagList\CountryTagList */
    protected function __get__countries() {
        return $this->countries;
    }
    /** @return bool */
protected function __get__free(){return $this->free;}


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
        $query = "SELECT A.enabled, B.* , A.track_language,A.seasons_count,A.series_count,A.free,
            COALESCE(TL1.name,TL2.name) track_language_name            
            FROM media__content A JOIN  media__content__gif B ON (A.id=B.id) 
            LEFT JOIN media__content__tracklang__strings TL1 ON(TL1.id=A.track_language AND TL1.language_id='%s')
            LEFT JOIN media__content__tracklang__strings TL2 ON(TL2.id=A.track_language AND TL2.language_id='%s')
            WHERE A.id=:P";
        $row = \DB\DB::F()->queryRow(sprintf($query, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language()), [":P" => $id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'common_name' => ['Strip', 'Trim', 'NEString'], //string
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'enabled' => ['Boolean', 'DefaultFalse'], //bool
            'free' => ['Boolean', 'DefaultFalse'], //bool
            'cdn_id' => ['Trim', 'NEString', 'DefaultNull'], //string
            'cdn_url' => ['Trim', 'NEString', 'DefaultNull'], //string
            'target' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'series_count' => ['IntMore0', 'DefaultNull'], //int
            'seasons_count' => ['IntMore0', 'DefaultNull'], //int            
            'track_language' => ['IntMore0', 'DefaultNull'], //int
            'track_language_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

    protected function t_common_import_after_import() {
        $this->load_strings();
        $this->tags = \Content\MediaContent\TagList\TagTagList::F($this->id);
        $this->countries = \Content\MediaContent\TagList\CountryTagList::F($this->id);
        $this->genres = \Content\MediaContent\TagList\GenreTagList::F($this->id);
    }

    protected function load_strings() {
        $this->strings = [];
        $rows = \DB\DB::F()->queryAll("SELECT language_id,name `text`  FROM media__content__gif__strings WHERE id=:P", [":P" => $this->id]);
        foreach ($rows as $row) {
            try {
                $crow = \Filters\FilterManager::F()->apply_filter_array($row, $this->get_lang_filters());
                \Filters\FilterManager::F()->raise_array_error($crow);
                if ($crow['text']) {
                    $this->strings[$crow['language_id']] = $crow;
                }
            } catch (\Throwable $e) {
                
            }
        }
    }

    protected function get_lang_filters() {
        return [
            'language_id' => ['Strip', 'Trim', 'NEString'],
            'text' => ['Strip', 'Trim', 'NEString', 'DefaultNull']
        ];
    }

}
