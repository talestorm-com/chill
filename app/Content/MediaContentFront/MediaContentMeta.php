<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $og_description
 * @property string $keywords
 * @property string $og_title
 * @property string $og_image_context
 * @property string $og_image_owner
 * @property string $og_image_id
 */
class MediaContentMeta implements \Out\Metadata\IMetadataSupport, \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $title;

    /** @var string */
    protected $description;

    /** @var string */
    protected $og_description;

    /** @var string */
    protected $keywords;

    /** @var string */
    protected $og_title;

    /** @var string */
    protected $og_image_context;

    /** @var string */
    protected $og_image_owner;

    /** @var string */
    protected $og_image_id;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__title() {
        return $this->title;
    }

    /** @return string */
    protected function __get__description() {
        return $this->description;
    }

    /** @return string */
    protected function __get__og_description() {
        return $this->og_description;
    }

    /** @return string */
    protected function __get__keywords() {
        return $this->keywords;
    }

    /** @return string */
    protected function __get__og_title() {
        return $this->og_title;
    }

    /** @return string */
    protected function __get__og_image_context() {
        return $this->og_image_context;
    }

    /** @return string */
    protected function __get__og_image_owner() {
        return $this->og_image_owner;
    }

    /** @return string */
    protected function __get__og_image_id() {
        return $this->og_image_id;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Metasupport">

    public function meta_get_description(): string {
        return $this->description;
    }

    public function meta_get_keywords(): string {
        return $this->keywords;
    }

    public function meta_get_og_description(): string {
        return $this->og_description;
    }

    public function meta_get_og_image_context(): string {
        return $this->og_image_context;
    }

    public function meta_get_og_image_image(): string {
        return $this->og_image_id;
    }

    public function meta_get_og_image_owner(): string {
        return $this->og_image_owner;
    }

    public function meta_get_og_image_support(): bool {
        return true;
    }

    public function meta_get_og_support(): bool {
        return true;
    }

    public function meta_get_og_title(): string {
        return $this->og_title;
    }

    public function meta_get_title(): string {
        return $this->title;
    }

    //</editor-fold>

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        return new static($id, $language, $default_language);
    }

    public function __construct(int $id, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $this->load($id, $language, $default_language);
    }

    /**
     * 
     * @param int $id
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return $this
     */
    protected function load(int $id, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $query = "SELECT  
            A.id,
            COALESCE(M1.title,M2.title) title,
            COALESCE(M1.og_title,M2.og_title) og_title,
            COALESCE(M1.description,M2.description) description,
            COALESCE(M1.og_description,M2.og_description) og_description,
            COALESCE(M1.keywords,M2.keywords) keywords
            FROM media__content A 
            LEFT JOIN media__content__meta_lang_%s M1 ON(M1.id=A.id) 
            LEFT JOIN media__content__meta_lang_%s M2 ON(M2.id=A.id) 
            WHERE A.id=:P";
        $row = \DB\DB::F()->queryRow(sprintf($query, $language, $default_language), [":P" => $id]);
        
        $this->import_props($row);        
        return $this;
    }

    protected function t_common_import_get_filters(): array {        
        return [
            'id' => ['IntMore0', 'DefaultNull'], //int
            'title' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'description' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'og_description' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'keywords' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'og_title' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

}
