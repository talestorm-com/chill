<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Readers\Trailer;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property int $id
 * @property int $content_id
 * @property string[] $name
 * @property string $default_image
 * @property int $sort
 * @property bool $enabled
 * @property bool $free
 * @property bool $vertical
 * @property  \Content\MediaContent\FileList\FileList $files
 * @property int $series_count
 * @property int $seasons_count
 * @property string $target_url
 * @property int $track_language
 * @property string $track_language_name
 * @property \Content\MediaContent\TagList\GenreTagList $genres
 * @property \Content\MediaContent\TagList\CountryTagList $countries
 */
class MediaContentObject implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    const MEDIA_CONTEXT = "media_content_trailer";

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var int */
    protected $content_id;

    /** @var string[] */
    protected $name;

    /** @var string */
    protected $default_image;

    /** @var int */
    protected $sort;

    /** @var bool */
    protected $enabled;

    /** @var bool */
    protected $vertical;

    /** @var \Content\MediaContent\FileList\FileList */
    protected $files;

    /** @var int */
    protected $series_count;

    /** @var int */
    protected $seasons_count;

    /** @var string */
    protected $target_url;

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

    /** @return int */
    protected function __get__content_id() {
        return $this->content_id;
    }

    /** @return string[] */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__default_image() {
        return $this->default_image;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return bool */
    protected function __get__vertical() {
        return $this->vertical;
    }

    /** @return \Content\MediaContent\FileList\FileList */
    protected function __get__files() {
        return $this->files;
    }

    /** @return int */
    protected function __get__series_count() {
        return $this->series_count;
    }

    /** @return int */
    protected function __get__seasons_count() {
        return $this->seasons_count;
    }

    /** @return string */
    protected function __get__target_url() {
        return $this->target_url;
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
    protected function __get__free() {
        return $this->free;
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
        $query = "SELECT A.enabled,A.track_language,A.series_count,A.seasons_count,A.free, B.* ,
            COALESCE(L1.name,L2.name) track_language_name
            FROM media__content A JOIN  media__content__trailer B ON (A.id=B.id) 
            LEFT JOIN media__content__tracklang__strings L1 ON(L1.id=A.track_language AND L1.language_id='%s')
            LEFT JOIN media__content__tracklang__strings L2 ON(L2.id=A.track_language AND L2.language_id='%s')
            WHERE A.id=:P";
        $row = \DB\DB::F()->queryRow(sprintf($query, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language()), [":P" => $id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'content_id' => ['IntMore0'], //int
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'sort' => ['Int', 'Default0'], //int
            'enabled' => ['Boolean', 'DefaultFalse'], //bool
            'free' => ['Boolean', 'DefaultFalse'], //bool
            'vertical' => ['Boolean', 'DefaultFalse'], //bool
            'series_count' => ['IntMore0', 'DefaultNull'], //int
            'seasons_count' => ['IntMore0', 'DefaultNull'], //int
            'target_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'track_language' => ['IntMore0', 'DefaultNull'], //int
            'track_language_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

    protected function t_common_import_after_import() {
        $this->load_names();
        $this->genres = \Content\MediaContent\TagList\GenreTagList::F($this->id);
        $this->countries = \Content\MediaContent\TagList\CountryTagList::F($this->id);
        $this->files = \Content\MediaContent\FileList\TrailerFileList::F($this->id);
    }

    protected function load_names() {
        $this->name = [];
        $rows = \DB\DB::F()->queryAll("SELECT language_id,name  FROM media__content__trailer__strings WHERE id=:P", [":P" => $this->id]);
        foreach ($rows as $row) {
            try {
                $crow = \Filters\FilterManager::F()->apply_filter_array($row, $this->get_lang_filters());
                \Filters\FilterManager::F()->raise_array_error($crow);
                $this->name[$crow['language_id']] = $crow['name'];
            } catch (\Throwable $e) {
                
            }
        }
    }

    protected function get_lang_filters() {
        return [
            'language_id' => ['Strip', 'Trim', 'NEString'],
            'name' => ['Strip', 'Trim', 'NEString'],
        ];
    }

}
