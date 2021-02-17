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
 * @property MediaContentLangSplitListSOAP $soap
 * @property MediaContentLangSplitListVIDEO $video
 * @property string $lang_name
 * @property int $lang_id 
 */
class MediaContentLangSplitList extends \Content\Content {

    /** @var MediaContentLangSplitListSOAP */
    protected $soap;

    /** @var MediaContentLangSplitListVIDEO */
    protected $video;

    /** @var string */
    protected $lang_name;

    /** @var int */
    protected $lang_id;

    /** @return MediaContentLangSplitListSOAP */
    protected function __get__soap() {
        return $this->soap;
    }

    /** @return MediaContentLangSplitListSOAP */
    protected function __get__video() {
        return $this->video;
    }

    /** @return string */
    protected function __get__lang_name() {
        return $this->lang_name;
    }

    /** @return int */
    protected function __get__lang_id() {
        return $this->lang_id;
    }

    public function __construct(int $lang_id, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $this->load_support_data($lang_id, $language, $default_language);
        $this->soap = MediaContentLangSplitListSOAP::F($lang_id, $offset, $perpage, $language, $default_language);
        $this->video = MediaContentLangSplitListSOAP::F($lang_id, $offset, $perpage, $language, $default_language);
    }

    protected function load_support_data(int $lang_id, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $query = "
            SELECT A.id,COALESCE(S1.name,S2.name) name 
            FROM media__content__tracklang A 
            LEFT JOIN media__content__tracklang__strings S1 ON(A.id=S1.id AND S1.language_id='%s')
            LEFT JOIN media__content__tracklang__strings S2 ON(A.id=S2.id AND S2.language_id='%s')
            WHERE A.id=:P;
            ";
        $row = \DB\DB::F()->queryRow(sprintf($query, $language, $default_language), [":P" => $lang_id]);
        $emoji = \Filters\FilterManager::F()->apply_filter_array(is_array($row) ? $row : [], [
            'id' => ['IntMore0', 'Default0'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ]);
        $this->lang_id = $emoji['id'];
        $this->lang_name = $emoji['name'] ? $emoji['name'] : "unknown";
    }

    /**
     * 
     * @param int $lang_id
     * @param int $offset
     * @param int $perpage
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return \static
     */
    public static function F(int $lang_id, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        return new static($lang_id, $offset, $perpage, $language, $default_language);
    }

}
