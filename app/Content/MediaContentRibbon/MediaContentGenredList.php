<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentRibbon;

use Out\Out;

/**
 * Description of MediaContentGenredList
 *
 * @author eve
 * @property MediaContentGenredListSOAP $soap
 * @property MediaContentGenredListVIDEO $video
 * @property string $genre_name
 * @property int $genre_id
 */
class MediaContentGenredList extends \Content\Content {

    /** @var MediaContentGenredListSOAP */
    protected $soap;

    /** @var MediaContentGenredListVIDEO */
    protected $video;

    /** @var string */
    protected $genre_name;

    /** @var int */
    protected $genre_id;

    /** @var string */
    protected $additional_content;

    /** @return MediaContentGenredListSOAP */
    protected function __get__soap() {
        return $this->soap;
    }

    /** @return MediaContentGenredListVIDEO */
    protected function __get__video() {
        return $this->video;
    }

    /** @return string */
    protected function __get__genre_name() {
        return $this->genre_name;
    }

    /** @return int */
    protected function __get__genre_id() {
        return $this->genre_id;
    }

    /** @return string */
    protected function __get__additional_content() {
        return $this->additional_content;
    }

    public function __construct(int $genre_id, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $this->load_genre_data($genre_id, $language, $default_language);
        $this->soap = MediaContentGenredListSOAP::F($genre_id, $offset, $perpage, $language, $default_language);
        $this->video = MediaContentGenredListVIDEO::F($genre_id, $offset, $perpage, $language, $default_language);
    }

    protected function load_genre_data(int $genre_id, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $query = "
            SELECT A.id,COALESCE(S1.name,S2.name) name, A.meta_title, A.meta_description, A.additional_content
            FROM media__content__genre A 
            LEFT JOIN media__content__genre__strings S1 ON(A.id=S1.id AND S1.language_id='%s')
            LEFT JOIN media__content__genre__strings S2 ON(A.id=S2.id AND S2.language_id='%s')
            WHERE A.id=:P;
            ";
        $row = \DB\DB::F()->queryRow(sprintf($query, $language, $default_language), [":P" => $genre_id]);
        $genre = \Filters\FilterManager::F()->apply_filter_array($row, [
            'id' => ['IntMore0', 'Default0'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'meta_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'meta_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'additional_content' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
        ]);
        $this->genre_id = $genre['id'];
        $this->genre_name = $genre['name'] ? $genre['name'] : "Unknown";

        $this->additional_content = $genre['additional_content'];
        
        //Устанавливаем мета-теги title, description, если они есть, иначе остается значение по умолчанию
        $meta_manager = \Router\Router::F()->route->get_controller_class()::F()->get_meta_manager();
        if ($genre['meta_title']) {
            $meta_manager->show_title_prefix = false;
            $meta_manager->set_title($genre['meta_title']);
        }
        if ($genre['meta_description']) $meta_manager->set_description($genre['meta_description']);
        
    }

    /**
     * 
     * @param int $genre_id
     * @param int $offset
     * @param int $perpage
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return \static
     */
    public static function F(int $genre_id, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        return new static($genre_id, $offset, $perpage, $language, $default_language);
    }

}
