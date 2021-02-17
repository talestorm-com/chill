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
 * @property string $search_query
 * @property string $file_version
 * @property MediaContentRibbonSearchSOAP $soap
 * @property MediaContentRibbonSearchGIF $gifs
 * @property MediaContentRibbonSearchTEXT $news
 */
class MediaContentRibbonSearch extends \Content\Content {

    protected static $_file_version;

    /** @var string */
    protected $search_query;

    /** @var string */
    protected $file_version;

    /** @var MediaContentRibbonSearchSOAP */
    protected $soap;

    /** @var MediaContentRibbonSearchGIF */
    protected $gifs;

    /** @var MediaContentRibbonSearchTEXT */
    protected $news;

    /** @return string */
    protected function __get__search_query() {
        return $this->search_query;
    }

    /** @return MediaContentRibbonSearchSOAP */
    protected function __get__soap() {
        return $this->soap;
    }

    /** @return MediaContentRibbonSearchGIF */
    protected function __get__gifs() {
        return $this->gifs;
    }

    /** @return MediaContentRibbonSearchTEXT */
    protected function __get__news() {
        return $this->news;
    }

    public function __construct(string $search_query, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $this->search_query = \Filters\FilterManager::F()->apply_chain($search_query, ['SQLSafeString', 'DefaultEmptyString']);
        $this->soap = MediaContentRibbonSearchSOAP::F($this->search_query, $offset, $perpage, $language, $default_language);
      //  $this->gifs = MediaContentRibbonSearchGIF::F($this->search_query, $offset, $perpage, $language, $default_language);
      //  $this->news = MediaContentRibbonSearchTEXT::F($this->search_query, $offset, $perpage, $language, $default_language);
    }

    /**
     * 
     * @param string $search_query
     * @param int $offset
     * @param int $perpage
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return \static
     */
    public static function F(string $search_query, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        return new static($search_query, $offset, $perpage, $language, $default_language);
    }

    public function render(\Smarty $smarty = null, string $template = 'default', bool $return = false) {
        return parent::render($smarty, $template, $return);
    }

}
