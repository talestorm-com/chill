<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentRibbon;

/**
 * Description of MediaContentRibbonTag
 *
 * @author eve
 * @property int $tag_id
 * @property string $tag_name
 * @property int $requested_tag
 * @property string $file_version
 * @property MediaContentRibbonTagSOAP $soap
 * @property MediaContentRibbonTagGIF $gifs
 * @property MediaContentRibbonTagTEXT $news
 */
class MediaContentRibbonTag extends \Content\Content {

    protected static $_file_version;

    /** @var int */
    protected $tag_id;

    /** @var string */
    protected $tag_name;

    /** @var int */
    protected $requested_tag;

    /** @var string */
    protected $file_version;

    /** @var MediaContentRibbonTagSOAP */
    protected $soap;

    /** @var MediaContentRibbonTagGIF */
    protected $gifs;

    /** @var MediaContentRibbonTagTEXT */
    protected $news;

    /** @return int */
    protected function __get__tag_id() {
        return $this->tag_id;
    }

    /** @return string */
    protected function __get__tag_name() {
        return $this->tag_name;
    }

    /** @return int */
    protected function __get__requested_tag() {
        return $this->requested_tag;
    }

    /** @return MediaContentRibbonTagSOAP */
    protected function __get__soap() {
        return $this->soap;
    }

    /** @return MediaContentRibbonTagGIF */
    protected function __get__gifs() {
        return $this->gifs;
    }

    /** @return MediaContentRibbonTagTEXT */
    protected function __get__news() {
        return $this->news;
    }

    public function __construct(int $tag_id, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $this->requested_tag = $tag_id;
        $this->load_tag_data($tag_id, $language, $default_language);
        $this->soap = MediaContentRibbonTagSOAP::F($tag_id, $offset, $perpage, $language, $default_language);
        $this->gifs = MediaContentRibbonTagGIF::F($tag_id, $offset, $perpage, $language, $default_language);
        $this->news = MediaContentRibbonTagTEXT::F($tag_id, $offset, $perpage, $language, $default_language);
    }

    protected function load_tag_data(int $tag_id, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $tag_data = \DB\DB::F()->queryRow(sprintf("
            SELECT A.id,COALESCE(S1.name,S2.name) name
            FROM media__content__tag A 
            LEFT JOIN media__content__tag__strings S1 ON(S1.id=A.id AND S1.language_id='%s')
            LEFT JOIN media__content__tag__strings S2 ON(S2.id=A.id AND S2.language_id='%s')
            WHERE A.id=:P
            ", $language, $default_language), [":P" => $tag_id]);
        $tag = \Filters\FilterManager::F()->apply_filter_array(is_array($tag_data) ? $tag_data : [], [
            'id' => ['IntMore0', 'Default0'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull']
        ]);
        $this->tag_id = $tag['id'];
        $this->tag_name = $tag['name'] ? $tag['name'] : "#notfound";
    }

    /**
     * 
     * @param int $tag_id
     * @param int $offset
     * @param int $perpage
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return \static
     */
    public static function F(int $tag_id, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        return new static($tag_id, $offset, $perpage, $language, $default_language);
    }

    public function render(\Smarty $smarty = null, string $template = 'tagged', bool $return = false) {
        return parent::render($smarty, $template, $return);
    }

}
