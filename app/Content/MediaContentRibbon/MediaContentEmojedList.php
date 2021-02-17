<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentRibbon;

/**
 * Description of MediaContentEmojedList
 *
 * @author eve
 * @property MediaContentEmojedListSOAP $soap
 * @property MediaContentEmojedListVIDEO $video
 * @property string $emoji_name
 * @property int $emoji_id
 * @property int $emoji_tag
 */
class MediaContentEmojedList extends \Content\Content {

    /** @var MediaContentEmojedListSOAP */
    protected $soap;

    /** @var MediaContentEmojedListVIDEO */
    protected $video;

    /** @var string */
    protected $emoji_name;

    /** @var int */
    protected $emoji_id;

    /** @var int */
    protected $emoji_tag;

    /** @var string */
    protected $additional_content;

    /** @return MediaContentEmojedListSOAP */
    protected function __get__soap() {
        return $this->soap;
    }

    /** @return MediaContentEmojedListVIDEO */
    protected function __get__video() {
        return $this->video;
    }

    /** @return string */
    protected function __get__emoji_name() {
        return $this->emoji_name;
    }

    /** @return int */
    protected function __get__emoji_id() {
        return $this->emoji_id;
    }

    /** @return int */
    protected function __get__emoji_tag() {
        return $this->emoji_tag;
    }

    /** @return string */
    protected function __get__additional_content() {
        return $this->additional_content;
    }

    public function __construct(int $emoji_id, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $this->load_emoji_data($emoji_id, $language, $default_language);
        $this->soap = MediaContentEmojedListSOAP::F($emoji_id, $offset, $perpage, $language, $default_language);
        $this->video = MediaContentEmojedListVIDEO::F($emoji_id, $offset, $perpage, $language, $default_language);
    }

    protected function load_emoji_data(int $emoji_id, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        $query = "
            SELECT A.id,A.tag,COALESCE(S1.name,S2.name) name, A.meta_title, A.meta_description, A.additional_content 
            FROM media__emoji A 
            LEFT JOIN media__emoji__strings S1 ON(A.id=S1.id AND S1.language_id='%s')
            LEFT JOIN media__emoji__strings S2 ON(A.id=S2.id AND S2.language_id='%s')
            WHERE A.id=:P;
            ";
        $row = \DB\DB::F()->queryRow(sprintf($query, $language, $default_language), [":P" => $emoji_id]);
        $emoji = \Filters\FilterManager::F()->apply_filter_array(is_array($row)?$row:[], [
            'id' => ['IntMore0', 'Default0'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'tag' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'meta_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'meta_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'additional_content' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
        ]);
        $this->emoji_id = $emoji['id'];
        $this->emoji_name = $emoji['name'] ? $emoji['name'] : "notfound";
        $this->emoji_tag = $emoji['tag'] ? $emoji['tag'] : "notfound";

        $this->additional_content = $emoji['additional_content'];
        
        //Устанавливаем мета-теги title, description, если они есть, иначе остается значение по умолчанию
        $meta_manager = \Router\Router::F()->route->get_controller_class()::F()->get_meta_manager();
        if ($emoji['meta_title']) {
            $meta_manager->show_title_prefix = false;
            $meta_manager->set_title($emoji['meta_title']);
        }
        if ($emoji['meta_description']) $meta_manager->set_description($emoji['meta_description']);
    }

    /**
     * 
     * @param int $emoji_id
     * @param int $offset
     * @param int $perpage
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return \static
     */
    public static function F(int $emoji_id, int $offset, int $perpage, \Language\LanguageItem $language, \Language\LanguageItem $default_language) {
        return new static($emoji_id, $offset, $perpage, $language, $default_language);
    }

}
