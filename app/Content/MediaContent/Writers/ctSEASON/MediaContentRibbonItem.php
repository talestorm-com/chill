<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentRibbon;

/**
 * Description of MediaContentRibbonItem
 *
 * @author eve
 * @property int $id  идент записи в ленте
 * @property int $content_id  идент привязанного контента
 * @property string $content_type тип привязанного контента
 * @property bool $enabled включен ли привязанный контент
 * @property string $name название приявязанного контента
 * @property string $image_context контекст картинки
 * @property string $image_owner id владельца картинки
 * @property string $image имя изображения
 * @property int $trailed_content id контента за трейлером, если это трейлер
 * @property string $trailed_content_type тип контента за трейлером, если это трейлер
 * @property string $trailed_video_name имя видео за трейлером, если это трейлер и указывает на видос
 * @property string $trailed_soap_name  имя сериала за трейлером, если это трейлер и указывает на сериал
 * @property string $trailed_season_name  имя сезона за трейлером, если это трейлер и указывает на сезон
 * @property string $trailed_season_soap_name имя сериала для сезона за трейлером, если это трейлер и указывает на сезон
 * @property string $season_soap_name  имя сериала, если это сезон
 * @property int $season_soap_id  id сериала, если это сезон
 * @property string $series_season_name  имя сезона,если это серия
 * @property string $series_season_id  id сезона,если это серия
 * @property string $series_soap_name имя сериала, если это серия
 * @property string $series_soap_id id сериала, если это серия
 * @property string $display_type  отображаемый тип (слова трейлер,серал, GIF)
 * @property string $display_trailed_type отображаемый тип за трейлером (слова трейлер,серал, GIF)
 * @property string $banner_background_color
 * @property string $banner_foreground_color
 * @property string $banner_url
 * @property string $banner_text
 * @property boolean $banner_has_url
 * @property string $gif_cdn_url
 * @property string $text_short_text -- интро новости если это новость
 * @property int $tag_id
 * @property string $tag_name
 * @property boolean $has_tag
 * @property \DateTime $news_post
 * @property string $news_post_string
 * @property string $news_post_date_string
 * @property string $news_post_time_string
 * @property int $ratestars
 * @property string $gif_target_url
 * @property int $series_count
 * @property int $seasons_count
 * @property int $origin_country_id
 * @property string $origin_country_name
 * @property int $genre_id
 * @property string $genre_name
 * @property int $track_language
 * @property string $track_language_name
 * @property string $trailer_target_url
 * @property boolean $vertical
 * @property boolean $free
 * @property string $lent_mode
 * @property string $lent_message
 * @property string $lent_image_name
 * @property string $video_cdn_url 
 * @property string $video_cdn_id 
 * @property string $origin_language
 * @property int $age_restriction
 * @property string $age_restriction_tag
 * @property string $age_restriction_name
 * @property string $age_restriction_image
 * @property string $translit_name
 *

 */
class MediaContentRibbonItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    protected static $_file_version;

    public static function get_file_version() {
        if (!static::$_file_version) {
            static::$_file_version = md5(implode("+", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_file_version;
    }

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int    идент записи в ленте */
    protected $id;

    /** @var int    идент привязанного контента */
    protected $content_id;

    /** @var string   тип привязанного контента */
    protected $content_type;

    /** @var bool   включен ли привязанный контент */
    protected $enabled;

    /** @var string   название приявязанного контента */
    protected $name;

    /** @var string   контекст картинки */
    protected $image_context;

    /** @var string   id владельца картинки */
    protected $image_owner;

    /** @var string   имя изображения */
    protected $image;

    /** @var int   id контента за трейлером, если это трейлер */
    protected $trailed_content;

    /** @var string   тип контента за трейлером, если это трейлер */
    protected $trailed_content_type;

    /** @var string   имя видео за трейлером, если это трейлер и указывает на видос */
    protected $trailed_video_name;

    /** @var string    имя сериала за трейлером, если это трейлер и указывает на сериал */
    protected $trailed_soap_name;

    /** @var string    имя сезона за трейлером, если это трейлер и указывает на сезон */
    protected $trailed_season_name;

    /** @var string   имя сериала для сезона за трейлером, если это трейлер и указывает на сезон */
    protected $trailed_season_soap_name;

    /** @var string    имя сериала, если это сезон */
    protected $season_soap_name;

    /** @var string    имя сезона,если это серия */
    protected $series_season_name;

    /** @var string   имя сериала, если это серия */
    protected $series_soap_name;

    /** @var string */
    protected $banner_background_color;

    /** @var string */
    protected $banner_foreground_color;

    /** @var string */
    protected $banner_url;

    /** @var string */
    protected $banner_text;

    /** @var string */
    protected $gif_cdn_url;

    /** @var string */
    protected $text_short_text;

    /** @var int */
    protected $tag_id;

    /** @var string */
    protected $tag_name;

    /** @var string */
    protected $series_soap_id;

    /** @var int */
    protected $season_soap_id;

    /** @var string */
    protected $series_season_id;

    /** @var \DateTime */
    protected $news_post;

    /** @var int */
    protected $ratestars;

    /** @var string */
    protected $gif_target_url;

    /** @var int */
    protected $series_count;

    /** @var int */
    protected $seasons_count;

    /** @var int */
    protected $origin_country_id;

    /** @var string */
    protected $origin_country_name;

    /** @var int */
    protected $genre_id;

    /** @var string */
    protected $genre_name;

    /** @var int */
    protected $track_language;

    /** @var string */
    protected $track_language_name;

    /** @var string */
    protected $trailer_target_url;

    /** @var boolean */
    protected $vertical;

    /** @var boolean */
    protected $free;

    /** @var string */
    protected $lent_mode;

    /** @var string */
    protected $lent_message;

    /** @var string */
    protected $lent_image_name;

    /** @var string */
    protected $video_cdn_url;

    /** @var string */
    protected $origin_language;

    /** @var int */
    protected $age_restriction;

    /** @var string */
    protected $age_restriction_tag;

    /** @var string */
    protected $age_restriction_name;

    /** @var string */
    protected $age_restriction_image;

    /** @var string */
    protected $video_cdn_id;
    /** @var string */
    protected $translit_name;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    protected function __get__video_cdn_id(){
        return $this->video_cdn_id;
    }
    
    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__content_id() {
        return $this->content_id;
    }

    /** @return string */
    protected function __get__content_type() {
        return $this->content_type;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__image_context() {
        return $this->image_context;
    }

    /** @return string */
    protected function __get__image_owner() {
        return $this->image_owner;
    }

    /** @return string */
    protected function __get__image() {
        return $this->image;
    }

    /** @return int */
    protected function __get__trailed_content() {
        return $this->trailed_content;
    }

    /** @return string */
    protected function __get__trailed_content_type() {
        return $this->trailed_content_type;
    }

    /** @return string */
    protected function __get__trailed_video_name() {
        return $this->trailed_video_name;
    }

    /** @return string */
    protected function __get__trailed_soap_name() {
        return $this->trailed_soap_name;
    }

    /** @return string */
    protected function __get__trailed_season_name() {
        return $this->trailed_season_name;
    }

    /** @return string */
    protected function __get__trailed_season_soap_name() {
        return $this->trailed_season_soap_name;
    }

    /** @return string */
    protected function __get__season_soap_name() {
        return $this->season_soap_name;
    }

    /** @return string */
    protected function __get__series_season_name() {
        return $this->series_season_name;
    }

    /** @return string */
    protected function __get__series_soap_name() {
        return $this->series_soap_name;
    }

    /** @return string */
    protected function __get__banner_background_color() {
        return $this->banner_background_color;
    }

    /** @return string */
    protected function __get__banner_foreground_color() {
        return $this->banner_foreground_color;
    }

    /** @return string */
    protected function __get__banner_url() {
        return $this->banner_url;
    }

    /** @return string */
    protected function __get__banner_text() {
        return $this->banner_text;
    }

    /** @return string */
    protected function __get__gif_cdn_url() {
        return $this->gif_cdn_url;
    }

    /** @return string */
    protected function __get__text_short_text() {
        return $this->text_short_text;
    }

    /** @return int */
    protected function __get__tag_id() {
        return $this->tag_id;
    }

    /** @return string */
    protected function __get__tag_name() {
        return $this->tag_name;
    }

    /** @return string */
    protected function __get__series_soap_id() {
        return $this->series_soap_id;
    }

    /** @return int */
    protected function __get__season_soap_id() {
        return $this->season_soap_id;
    }

    /** @return string */
    protected function __get__series_season_id() {
        return $this->series_season_id;
    }

    /** @return \DateTime */
    protected function __get__news_post() {
        return $this->news_post;
    }

    /** @return int */
    protected function __get__ratestars() {
        return $this->ratestars;
    }

    /** @return string */
    protected function __get__gif_target_url() {
        return $this->gif_target_url;
    }

    /** @return int */
    protected function __get__series_count() {
        return $this->series_count;
    }

    /** @return int */
    protected function __get__seasons_count() {
        return $this->seasons_count;
    }

    /** @return int */
    protected function __get__origin_country_id() {
        return $this->origin_country_id;
    }

    /** @return string */
    protected function __get__origin_country_name() {
        return $this->origin_country_name;
    }

    /** @return int */
    protected function __get__genre_id() {
        return $this->genre_id;
    }

    /** @return string */
    protected function __get__genre_name() {
        return $this->genre_name;
    }

    /** @return int */
    protected function __get__track_language() {
        return $this->track_language;
    }

    /** @return string */
    protected function __get__track_language_name() {
        return $this->track_language_name;
    }

    /** @return string */
    protected function __get__trailer_target_url() {
        return $this->trailer_target_url;
    }

    /** @return boolean */
    protected function __get__vertical() {
        return $this->vertical;
    }

    /** @return boolean */
    protected function __get__free() {
        return $this->free;
    }

    /** @return string */
    protected function __get__lent_mode() {
        return $this->lent_mode;
    }

    /** @return string */
    protected function __get__lent_message() {
        return $this->lent_message;
    }

    /** @return string */
    protected function __get__lent_image_name() {
        return $this->lent_image_name;
    }

    /** @return string */
    protected function __get__video_cdn_url() {
        return $this->video_cdn_url;
    }

    /** @return string */
    protected function __get__origin_language() {
        return $this->origin_language;
    }

    /** @return  int */
    protected function __get__age_restriction() {
        return $this->age_restriction;
    }

    /** @return  string */
    protected function __get__age_restriction_tag() {
        return $this->age_restriction_tag;
    }

    /** @return string */
    protected function __get__age_restriction_name() {
        return $this->age_restriction_name;
    }

    /** @return string */
    protected function __get__age_restriction_image() {
        return $this->age_restriction_image;
    }

    /** @return string */
    protected function __get__translit_name() {
        return $this->translit_name;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="virtual getters">
    protected function __get__display_type() {
        return ['ctTRAILER' => 'Трейлер', 'ctVIDEO' => 'Фильм', 'ctSEASON' => 'Сериал', 'ctSEASONSEASON' => 'Сезон', 'ctGIF' => 'GIF', 'ctNEWS' => 'Новость', 'ctBANNER' => ''][$this->content_type];
    }

    protected function __get__display_trailed_type() {
        return ['ctVIDEO' => 'Фильм', 'ctSEASON' => 'Сериал', 'ctSEASONSEASON' => 'Сезон',][$this->trailed_content_type];
    }

    /** @return boolean */
    protected function __get__banner_has_url() {
        return $this->banner_url ? true : false;
    }

    public function get_image_url() {
        if ($this->lent_image_name) {
            return "lent_poster/{$this->image_owner}/{$this->lent_image_name}";
        }
        if ($this->image_context && $this->image_owner && $this->image) {
            return "{$this->image_context}/{$this->image_owner}/{$this->image}";
        } else {
            return "fallback/1/{$this->image_context}";
        }
    }

    /** @return boolean */
    protected function __get__has_tag() {
        return $this->tag_id ? true : false;
    }

    /** @return string */
    protected function __get__news_post_string() {
        return $this->news_post ? $this->news_post->format('d.m.Y H:i') : null;
    }

    /** @return string */
    protected function __get__news_post_date_string() {
        return $this->news_post ? $this->news_post->format('d.m.Y') : null;
    }

    /** @return string */
    protected function __get__news_post_time_string() {
        return $this->news_post ? $this->news_post->format('H:i') : null;
    }

    //</editor-fold>



    protected function __construct(array $data) {
        $this->import_props($data);
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data) {
        return new static($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'content_id' => ['IntMore0'], //int
            'content_type' => ['Trim', 'NEString'], //string
            'enabled' => ['Boolean', 'DefaultFalse'], //bool
            'free' => ['Boolean', 'DefaultFalse'], //bool
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'image_context' => ['Trim', 'NEString', 'DefaultNull'], //string
            'image_owner' => ['Trim', 'NEString', 'DefaultNull'], //string
            'image' => ['Trim', 'NEString', 'DefaultNull'], //string
            'trailed_content' => ['IntMore0', 'DefaultNull'], //int
            'trailed_content_type' => ['Trim', 'NEString', 'DefaultNull'], //string
            'trailed_video_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'trailed_soap_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'trailed_season_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'trailed_season_soap_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'season_soap_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'series_season_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //type
            'series_soap_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //type
            'banner_background_color' => ['HTMLColor', 'DefaultNull'], //string
            'banner_foreground_color' => ['HTMLColor', 'DefaultNull'], //string
            'banner_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'banner_text' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string    
            'gif_cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string     
            'text_short_text' => ['Trim', 'NEString', 'DefaultNull'], //string
            'tag_id' => ['IntMore0', 'DefaultNull'], //int
            'tag_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //int
            'series_soap_id' => ['IntMore0', 'DefaultNull'], //int
            'season_soap_id' => ['IntMore0', 'DefaultNull'], //int
            'series_season_id' => ['IntMore0', 'DefaultNull'], //int
            'news_post' => ['DateMatch', 'DefaultNull'],
            'ratestars' => ['IntMore0', 'Default0'],
            'gif_target_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'series_count' => ['IntMore0', 'DefaultNull'], //int
            'seasons_count' => ['IntMore0', 'DefaultNull'], //int
            'origin_country_id' => ['IntMore0', 'DefaultNull'], //int
            'origin_country_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'genre_id' => ['IntMore0', 'DefaultNull'], //int
            'genre_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'track_language' => ['IntMore0', 'DefaultNull'], //int
            'track_language_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'trailer_target_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'vertical' => ['Boolean', 'DefaultFalse'],
            'lent_mode' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string     
            'lent_message' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string     
            'lent_image_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string     
            'video_cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string     
            'video_cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string     
            'origin_language' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'age_restriction' => ['IntMore0', 'DefaultNull'], //
            'age_restriction_tag' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //
            'age_restriction_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //
            'age_restriction_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'translit_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

    protected function t_common_import_after_import() {
        $method = "after_import_{$this->content_type}";
        $raw = $this->marshall();
        if (method_exists($this, $method)) {
            $this->$method($raw);
        }
    }

    protected function t_default_marshaller_export_property_news_post() {
        return $this->news_post ? $this->news_post->format('d.m.Y H:i') : null;
    }

//    protected function after_import_ctVIDEO(array $raw) {
//        $crow = \Filters\FilterManager::F()->apply_filter_array($raw, $this->get_filters_ctVIDEO());
//        \Filters\FilterManager::F()->raise_array_error($crow);
//    }
//    
//    protected function get_filters_ctVIDEO(){
//        return [
//            
//        ];
//    }
//    protected function after_import_ctBANNER(array $raw) {
//        $ct = \Filters\FilterManager::F()->apply_filter_array($raw, $this->get_filters_ctSEASONSEASON());
//        \Filters\FilterManager::F()->raise_array_error($ct);
//    }
    protected function after_import_ctGIF(array $raw) {
        $ct = \Filters\FilterManager::F()->apply_filter_array($raw, $this->get_filters_ctGIF());
        \Filters\FilterManager::F()->raise_array_error($ct);
    }

    protected function after_import_ctTEXT(array $raw) {
        $ct = \Filters\FilterManager::F()->apply_filter_array($raw, $this->get_filters_ctTEXT());
        \Filters\FilterManager::F()->raise_array_error($ct);
    }

    protected function after_import_ctSEASON(array $raw) {
        if ($this->lent_mode === 'gif' && !$this->gif_cdn_url) {
            $this->lent_mode = 'poster';
        } else if ($this->lent_mode === 'video' && !$this->video_cdn_url) {
            $this->lent_mode = 'poster';
        }
    }

    protected function after_import_ctSEASONSEASON(array $raw) {
        $ct = \Filters\FilterManager::F()->apply_filter_array($raw, $this->get_filters_ctSEASONSEASON());
        \Filters\FilterManager::F()->raise_array_error($ct);
    }

    protected function after_import_ctSEASONSERIES(array $raw) {
        $ct = \Filters\FilterManager::F()->apply_filter_array($raw, $this->get_filters_ctSEASONSERIES());
        \Filters\FilterManager::F()->raise_array_error($ct);
    }

    protected function after_import_ctTRAILER(array $raw) {
        $ct = \Filters\FilterManager::F()->apply_filter_array($raw, $this->get_filters_ctTRAILER());
        \Filters\FilterManager::F()->raise_array_error($ct);
        $ctm = "after_import_ctTRAILER_{$this->trailed_content_type}";
        if (method_exists($this, $ctm)) {
            $this->$ctm($raw);
        }
    }

    protected function after_import_ctTRAILER_ctVIDEO(array $raw) {
        $ct = \Filters\FilterManager::F()->apply_filter_array($raw, $this->get_filters_ctTRAILER_ctVIDEO());
        \Filters\FilterManager::F()->raise_array_error($ct);
    }

    protected function after_import_ctTRAILER_ctSEASON(array $raw) {
        $ct = \Filters\FilterManager::F()->apply_filter_array($raw, $this->get_filters_ctTRAILER_ctSEASON());
        \Filters\FilterManager::F()->raise_array_error($ct);
    }

    protected function after_import_ctTRAILER_ctSEASONSEASON(array $raw) {
        $ct = \Filters\FilterManager::F()->apply_filter_array($raw, $this->get_filters_ctTRAILER_ctSEASONSEASON());
        \Filters\FilterManager::F()->raise_array_error($ct);
    }

    function get_filters_ctTRAILER() {
        return [
            'trailed_content' => ['IntMore0'], //int
            'trailed_content_type' => ['Trim', 'NEString'], //string
        ];
    }

    protected function get_filters_ctTRAILER_ctVIDEO() {
        return [
            'trailed_video_name' => ['Strip', 'Trim', 'NEString',], //string
        ];
    }

    protected function get_filters_ctTRAILER_ctSEASON() {
        return [
            'trailed_soap_name' => ['Strip', 'Trim', 'NEString',], //string
        ];
    }

    protected function get_filters_ctTRAILER_ctSEASONSEASON() {
        return [
            'trailed_season_name' => ['Strip', 'Trim', 'NEString',], //string
            'trailed_season_soap_name' => ['Strip', 'Trim', 'NEString',], //string 
        ];
    }

    protected function get_filters_ctSEASONSEASON() {
        return [
            'season_soap_name' => ['Strip', 'Trim', 'NEString',], //string  
        ];
    }

    protected function get_filters_ctSEASONSERIES() {
        return [
            'series_season_name' => ['Strip', 'Trim', 'NEString',], //type
            'series_soap_name' => ['Strip', 'Trim', 'NEString',], //type
        ];
    }

    protected function get_filters_ctGIF() {
        return [
            'gif_cdn_url' => ['Strip', 'Trim', 'NEString',], //string            
        ];
    }

    protected function get_filters_ctTEXT() {
        return [
            'text_short_text' => ['Trim', 'NEString', 'DefaultEmptyString'], //string            
        ];
    }

}
