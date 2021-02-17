<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront\MediaContentFrontSOAP;

/**
 * Description of SeasonListItem
 *
 * @author eve
 * @property int $id
 * @property int $soap_id
 * @property string $common_name
 * @property string $name
 * @property int $num
 * @property string $intro
 * @property string $info
 * @property string $default_poster
 * @property SeriesList $series
 * @property \Content\MediaContentFront\TrailerList\TrailerList $trailers
 * @property \Content\IImageCollection $images
 * 
 */
class SeasonListItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var int */
    protected $soap_id;

    /** @var string */
    protected $common_name;

    /** @var string */
    protected $name;

    /** @var int */
    protected $num;

    /** @var string */
    protected $intro;

    /** @var string */
    protected $info;

    /** @var string */
    protected $default_poster;

    /** @var SeriesList */
    protected $series;

    /** @var \Content\MediaContentFront\TrailerList\TrailerList */
    protected $trailers;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var \Language\LanguageItem  */
    protected $language;

    /** @var \Language\LanguageItem */
    protected $default_language;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__soap_id() {
        return $this->soap_id;
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
    protected function __get__num() {
        return $this->num;
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

    /** @return SeriesList */
    protected function __get__series() {
        return $this->series;
    }

    /** @return \Content\MediaContentFront\TrailerList\TrailerList */
    protected function __get__trailers() {
        return $this->trailers;
    }

    /** @return \Content\IImageCollection */
    protected function __get__images() {
        return $this->images;
    }

    //</editor-fold>


    public function __construct(array $data, \Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null) {
        $this->language = $language;
        $this->default_language = $default_language;
        $this->import_props($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'soap_id' => ['IntMore0'], //int
            'common_name' => ['Strip', 'Trim', 'NEString'], //string
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'num' => ['IntMore0', 'Default0'], //int
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

    protected function t_common_import_after_import() {
        if ($this->id) {
            $this->trailers = \Content\MediaContentFront\TrailerList\TrailerList::F()
                    ->load(
                    $this->id,
                    $this->language ? $this->language : \Language\LanguageList::F()->get_current_language(),
                    $this->default_language ? $this->default_language : \Language\LanguageList::F()->get_default_language()
            );
            $this->series = SeriesList::F()
                    ->load(
                    $this->id,
                    $this->language ? $this->language : \Language\LanguageList::F()->get_current_language(),
                    $this->default_language ? $this->default_language : \Language\LanguageList::F()->get_default_language()
            );
            $this->images = \Content\DefaultImageCollection::F(\Content\MediaContent\Readers\ctSEASONSEASON\MediaContentObject::MEDIA_CONTEXT_POSTERS, $this->id);
        }
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data, \Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null) {
        return new static($data, $language, $default_language);
    }

}
