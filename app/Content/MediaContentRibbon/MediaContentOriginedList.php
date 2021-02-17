<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentRibbon;

/**
 * Description of MediaContentOriginedList
 *
 * @author eve
 * @property MediaContentOriginedListSOAP $soap
 * @property MediaContentOriginedListVIDEO $video
 * @property string $origin_name
 * @property int $origin_id 
 */
class MediaContentOriginedList extends \Content\Content {

    /** @var MediaContentOriginedListSOAP */
    protected $soap;

    /** @var MediaContentOriginedListVIDEO */
    protected $video;

    /** @var string */
    protected $origin_name;

    /** @var int */
    protected $origin_id;

    /** @return MediaContentOriginedListSOAP */
    protected function __get__soap() {
        return $this->soap;
    }

    /** @return MediaContentOriginedListVIDEO */
    protected function __get__video() {
        return $this->video;
    }

    /** @return string */
    protected function __get__origin_name() {
        return $this->origin_name;
    }

    /** @return int */
    protected function __get__origin_id() {
        return $this->origin_id;
    }

    public function __construct(int $origin_id, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $this->load_origin_data($origin_id, $language, $default_language);
        $this->soap = MediaContentOriginedListSOAP::F($origin_id, $offset, $perpage, $language, $default_language);
        $this->video = MediaContentOriginedListVIDEO::F($origin_id, $offset, $perpage, $language, $default_language);
    }

    protected function load_origin_data(int $origin_id, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $query = "
            SELECT A.id,COALESCE(S1.name,S2.name) name 
            FROM media__content__origin_country A 
            LEFT JOIN media__content__origin__country__strings S1 ON(A.id=S1.id AND S1.language_id='%s')
            LEFT JOIN media__content__origin__country__strings S2 ON(A.id=S2.id AND S2.language_id='%s')
            WHERE A.id=:P;
            ";
        $row = \DB\DB::F()->queryRow(sprintf($query, $language, $default_language), [":P" => $origin_id]);
        $emoji = \Filters\FilterManager::F()->apply_filter_array(is_array($row) ? $row : [], [
            'id' => ['IntMore0', 'Default0'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ]);
        $this->origin_id = $emoji['id'];
        $this->origin_name = $emoji['name'] ? $emoji['name'] : "unknown";
    }

    /**
     * 
     * @param int $origin_id
     * @param int $offset
     * @param int $perpage
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return \static
     */
    public static function F(int $origin_id, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        return new static($origin_id, $offset, $perpage, $language, $default_language);
    }

}
