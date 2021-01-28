<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentRibbon;

/**
 * Description of MediaContentRibbon
 *
 * @author eve
 * @property string $file_version
 */
class MediaContentRibbon extends MediaContentRibbonAbstract {

    protected static $_file_version;

    /** @var string */
    protected $file_version;

    protected function __get__file_version() {
        return $this->file_version;
    }

    public static function get_file_version() {
        if (!static::$_file_version) {
            static::$_file_version = md5(implode("-", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_file_version;
    }

    protected function __construct(\Language\LanguageItem $language, int $offset, int $perpage, bool $display_disabled) {
        $this->file_version = static::get_file_version();
        $this->load($language, $offset, $perpage, $display_disabled);
        $this->cache($language, $offset, $perpage, $display_disabled);
    }

    protected function cache(\Language\LanguageItem $language, int $offset, int $perpage, bool $display_disabled) {
        $key = static::mk_cache_key($language, $offset, $perpage, $display_disabled);
        $cache = \Cache\FileCache::F();
        //$cache->put($key, $this, 0, \Cache\FileBeaconDependency::F([]));
    }

    protected static function mk_cache_key(\Language\LanguageItem $language, int $offset, int $perpage, bool $display_disabled) {
        return implode(":::", [__CLASS__, $language->id, $offset, $perpage, $display_disabled ? "T" : "F"]);
    }

    /**
     * 
     * @param \Language\LanguageItem $language
     * @param int $offset
     * @param int $perpage
     * @return \static
     */
    public static function F(\Language\LanguageItem $language, int $offset = 0, int $perpage = 100, bool $display_disabled = false) {
        $cache_key = static::mk_cache_key($language, $offset, $perpage, $display_disabled);
        $cached = \Cache\FileCache::F()->get($cache_key);
        $cs = static::class;
        if ($cached && is_object($cached) && ($cached instanceof $cs) && $cached->file_version === static::get_file_version()) {
            return $cached;
        }
        return new static($language, $offset, $perpage, $display_disabled);
    }

}
