<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront\TrailerList;

/**
 * @property int $id
 * @property string $name
 * @property bool $vertical
 * @property string $default_poster
 * @property \Content\MediaContentFront\FileList\FileList $files
 * @property \Content\IImageCollection $images
 */
class TrailerListItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $vertical;

    /** @var string */
    protected $default_poster;

    /** @var \Content\MediaContentFront\FileList\FileList */
    protected $files;

    /** @var \Content\IImageCollection */
    protected $images;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return bool */
    protected function __get__vertical() {
        return $this->vertical;
    }

    /** @return string */
    protected function __get__default_poster() {
        return $this->default_poster;
    }

    /** @return \Content\MediaContentFront\FileList\FileList */
    protected function __get__files() {
        return $this->files;
    }

    /** @return \Content\IImageCollection */
    protected function __get__images() {
        return $this->images;
    }

    //</editor-fold>


    public function __construct(array $data) {
        $this->import_props($data);
    }
    
    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data){
        return new static($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'name' => ['Strip', 'Trim', 'NEString','DefaultEmptyString'], //string
            'vertical' => ['Boolean', 'DefaultFalse'], //bool
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

    protected function t_common_import_after_import() {
        if ($this->id) {
            $this->files = \Content\MediaContentFront\FileList\FileList::F()->load($this->id);
            $this->images = \Content\DefaultImageCollection::F(\Content\MediaContent\Readers\Trailer\MediaContentObject::MEDIA_CONTEXT, $this->id);
        }
    }
    
    /**
     * 
     * @param int $id
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return \static
     */
    public static function from_db(int $id, \Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null){
        $language ? 0 : $language = \Language\LanguageList::F()->get_current_language();
        $default_language ? 0 : $default_language = \Language\LanguageList::F()->get_default_language();        
        $query = "
             SELECT A.id,A.enabled,B.vertical,B.default_image,COALESCE(S1.name,S2.name) name             
            FROM media__content__trailer B
            JOIN media__content A ON(A.id=B.id)
            LEFT JOIN media__content__trailer__strings S1 ON(A.id=S1.id AND S1.language_id='%s')
            LEFT JOIN media__content__trailer__strings S2 ON(A.id=S2.id AND S2.language_id='%s')
            WHERE A.enabled=1 AND A.id=:P            
            ";
        $q = sprintf($query, $language, $default_language);
        $row = \DB\DB::F()->queryRow($q, [':P' => $id]);
        $row?0:\Errors\common_error::R("not found");
        return static::F($row);        
    }

}
