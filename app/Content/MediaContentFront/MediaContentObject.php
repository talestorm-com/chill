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
 * @property string $content_type
 * @property \Content\MediaContent\TagList\TagTagList $tags
 * @property MediaContentMeta $meta 
 * @property boolean $enabled
 * @property \Content\MediaContent\Properties $properties
 * @property \Content\MediaContent\PersonalList $persons
 * @property int $emoji_id
 * @property string $emoji_name
 * @property string $emoji_tag
 * @property int $age_restriction
 * @property string $age_restriction_tag
 * @property string $age_restriction_name
 * @property string $age_restriction_image
 * @property \Content\MediaContent\TagList\GenreTagList $genres
 * @property \Content\MediaContent\TagList\CountryTagList $countries
 * @property \Content\MediaContent\TagList\StudioTagList $studios
 * @property \Language\LanguageItem $language
 * @property \Language\LanguageItem $default_language
 * @property string $class_version
 */
abstract class MediaContentObject extends \Content\Content {

    use \common_accessors\TCommonImport;

    const TARGET_TYPE_TAG = 'ctNONE';

    private static $_class_version;

    /**
     * override in descedants!!!!!!!
     * @return string
     */
    public static function get_class_version() {
        if (!self::$_class_version) {
            self::$_class_version = md5(implode(",", [__FILE__, filemtime(__FILE__)]));
        }
        return self::$_class_version;
    }

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $content_type;

    /** @var \Content\MediaContent\TagList\TagTagList */
    protected $tags;

    /** @var MediaContentMeta */
    protected $meta;

    /** @var boolean */
    protected $enabled;

    /** @var \Content\MediaContent\Properties */
    protected $properties;

    /** @var \Content\MediaContent\PersonalList */
    protected $persons;

    /** @var int */
    protected $emoji_id;

    /** @var string */
    protected $emoji_name;

    /** @var string */
    protected $emoji_tag;

    /** @var int */
    protected $age_restriction;

    /** @var string */
    protected $age_restriction_tag;

    /** @var string */
    protected $age_restriction_name;
    /** @var string */
protected $age_restriction_image;


    /** @var \Content\MediaContent\TagList\GenreTagList */
    protected $genres;

    /** @var \Content\MediaContent\TagList\CountryTagList */
    protected $countries;

    /** @var \Content\MediaContent\TagList\StudioTagList */
    protected $studios;

    /** @var \Language\LanguageItem */
    protected $language;

    /** @var \Language\LanguageItem */
    protected $default_language;

    /** @var string */
    protected $class_version;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__content_type() {
        return $this->content_type;
    }

    /** @return \Content\MediaContent\TagList\TagTagList */
    protected function __get__tags() {
        return $this->tags;
    }

    /** @return MediaContentMeta */
    protected function __get__meta() {
        return $this->meta;
    }

    /** @return boolean */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return \Content\MediaContent\Properties */
    protected function __get__properties() {
        return $this->properties;
    }

    /** @return \Content\MediaContent\PersonalList */
    protected function __get__persons() {
        return $this->persons;
    }

    /** @return int */
    protected function __get__emoji_id() {
        return $this->emoji_id;
    }

    /** @return string */
    protected function __get__emoji_name() {
        return $this->emoji_name;
    }

    /** @return string */
    protected function __get__emoji_tag() {
        return $this->emoji_tag;
    }

    /** @return int */
    protected function __get__age_restriction() {
        return $this->age_restriction;
    }

    /** @return string */
    protected function __get__age_restriction_tag() {
        return $this->age_restriction_tag;
    }

    /** @return string */
    protected function __get__age_restriction_name() {
        return $this->age_restriction_name;
    }

    /** @return \Content\MediaContent\TagList\GenreTagList */
    protected function __get__genres() {
        return $this->genres;
    }

    /** @return \Content\MediaContent\TagList\CountryTagList */
    protected function __get__countries() {
        return $this->countries;
    }

    /** @return \Content\MediaContent\TagList\StudioTagList */
    protected function __get__studios() {
        return $this->studios;
    }

    /** @return \Language\LanguageItem */
    protected function __get__language() {
        return $this->language;
    }

    /** @return \Language\LanguageItem */
    protected function __get__default_language() {
        return $this->default_language;
    }

    /** @return string */
    protected function __get__class_version() {
        return $this->class_version;
    }
    /** @return string */
protected function __get__age_restriction_image(){return $this->age_restriction_image;}


    //</editor-fold>



    public function __construct(\Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null) {
        $this->language = $language ? $language : \Language\LanguageList::F()->get_current_language();
        $this->default_language = $default_language ? $default_language : \Language\LanguageList::F()->get_default_language();
        $this->class_version = static::get_class_version();
    }

    abstract public function load(int $id);

    public function load_array(array $data) {
        $this->import_props($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'content_type' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'enabled' => ['Boolean', 'DefaultFalse'], //boolean
            'emoji_id' => ['IntMore0', 'DefaultNull'], //int
            'emoji_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'emoji_tag' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'age_restriction' => ['IntMore0', 'DefaultNull'], //int
            'age_restriction_tag' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'age_restriction_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string       
            'age_restriction_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string       
        ];
    }

    protected function t_common_import_after_import() {
        parent::t_common_import_after_import();
        if ($this->id) {
            $this->meta = MediaContentMeta::F($this->id, $this->language, $this->default_language);
            $this->persons = \Content\MediaContent\PersonalList::F()->load($this->id, $this->language, $this->default_language);
            $this->properties = \Content\MediaContent\Properties::F()->load_from_database($this->id);
            $this->tags = \Content\MediaContent\TagList\TagTagList::F()->load($this->id, $this->language, $this->default_language);
            $this->genres = \Content\MediaContent\TagList\GenreTagList::F()->load($this->id, $this->language, $this->default_language);
            $this->countries = \Content\MediaContent\TagList\CountryTagList::F()->load($this->id, $this->language, $this->default_language);
            $this->studios = \Content\MediaContent\TagList\StudioTagList::F()->load($this->id, $this->language, $this->default_language);
        }
    }

    /**
     * 
     * @param int $id
     * @param \Language\LanguageItem $language
     * @return string
     */
    public static function mk_cache_key(int $id, \Language\LanguageItem $language) {
        return implode("-", [static::class, $id, $language->id, static::TARGET_TYPE_TAG]);
    }

    /**
     * 
     * @param int $id
     * @param \Language\LanguageItem $language
     * @return \static|null
     */
    public static function load_cached(int $id, \Language\LanguageItem $language) {
        $cache = \Cache\FileCache::F();
        $cache_key = static::mk_cache_key($id, $language);
        $cs = static::class;
        $item = $cache->get($cache_key);
        if ($item && is_object($item) && ($item instanceof $cs) && ($item->class_version === static::get_class_version())) {
            return $item;
        }
        return null;
    }

    /**
     * 
     * @return $this
     */
    public function put_to_cache() {
        if ($this->id) {
            $cache = \Cache\FileCache::F();
            $cache_key = static::mk_cache_key($this->id, $this->language);
            //$cache->put($cache_key, $this, 0, \Cache\FileBeaconDependency::F());                    
        }
        return $this;
    }

    /**
     * 
     * @param string $query
     * @return string
     */
    protected function sprintf_query(string $query) {
        $q = mb_substr_count($query, '%s');
        $qa = [];
        while (count($qa) < $q) {
            $qa[] = $this->language;
            $qa[] = $this->default_language;
        }
        return call_user_func_array('sprintf', array_merge([$query], $qa));
    }

    /**
     * 
     * @param int $id
     * @param \Language\LanguageItem $language
     * @param \Language\LanguageItem $default_language
     * @return \static
     */
    public static function FACTORY(int $id, \Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null) {
        $language ? 0 : $language = \Language\LanguageList::F()->get_current_language();
        $x = static::load_cached($id, $language);
        if ($x) {
            return $x;
        }        
        $m = new static($language, $default_language);
        $m->load($id);
        return $m;
    }

}
