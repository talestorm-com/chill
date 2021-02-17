<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of LanguageTokenList
 *
 * @author eve
 * @property string $language_id
 * @property string[] $tokens
 * @property string $class_version
 */
class LanguageTokenList implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    const CACHE_BEAKON = "languagetokenlist";

    protected static $instances;

    /** @var string */
    protected $language_id;

    /** @var string[] */
    protected $tokens;

    /** @var string */
    protected $class_version;

    //<editor-fold defaultstate="collapsed" desc="simple getters">

    /** @return string */
    protected function __get__language_id() {
        return $this->language_id;
    }

    /** @return string[] */
    protected function __get__tokens() {
        return $this->tokens;
    }

    /** @return string */
    protected function __get__class_version() {
        return $this->class_version;
    }

    //</editor-fold>

    /**
     * 
     * @param string $language_id
     * @return string
     */
    protected static function mk_cache_key(string $language_id): string {
        return md5(implode("*", [__CLASS__, $language_id]));
    }

    /**
     * 
     * @return string
     */
    protected static function get_class_ver(): string {
        return md5(implode("-", [__FILE__, filemtime(__FILE__)]));
    }

    /**
     * 
     * @param string $language_id
     * @return \Language\LanguageTokenList
     */
    public static function F(string $language_id = null): LanguageTokenList {
        $language = $language_id ? $language_id : LanguageList::F()->get_current_language()->id;
        if (!is_array(static::$instances)) {
            static::$instances = [];
        }
        $rtc = array_key_exists($language, static::$instances) ? static::$instances[$language] : null;
        if (!$rtc) {
            static::$instances[$language] = static::factory($language);
        }
        return static::$instances[$language];
    }

    /**
     * 
     * @param string $language_id
     * @return \static
     */
    protected function factory(string $language_id) {
        $cache = \Cache\FileCache::F();
        $cache_key = static::mk_cache_key($language_id);
        $cs = static::class; 
        $some = $cache->get($cache_key);
        if ( $some && is_object($some) && ($some instanceof $cs) && ($some->class_version === static::get_class_ver())) {
            return $some;
        }
        return new static($language_id);
    }

    protected function __construct(string $language_id) {
        $this->language_id = $language_id;
        $this->class_version = static::get_class_ver();
        $this->tokens = [];
        $this->load();
        $this->cache();
    }

    protected function load() {
        if ($this->language_id !== LanguageList::F()->get_default_language()->id) {
            $query = "SELECT A.l,COALESCE(B.t,A.t) t FROM 
            chill__frontend__language A 
            LEFT JOIN chill__frontend__language B ON(A.l=B.l AND B.language_id=:Pl)
            WHERE A.language_id=:Pd";
            $rows = \DB\DB::F()->queryAll($query, [":Pl" => $this->language_id, ":Pd" => LanguageList::F()->get_default_language()]);
        } else {
            $query = "SELECT l, t FROM 
            chill__frontend__language              
            WHERE language_id=:Pd";
            $rows = \DB\DB::F()->queryAll($query, [":Pd" => $this->language_id]);
        }
        foreach ($rows as $row) {
            $this->tokens[$row['l']] = $row['t'];
        }
    }

    protected function cache() {
        $cache = \Cache\FileCache::F();
        $cache_key = static::mk_cache_key($this->language_id);
        $cache->put($cache_key, $this, 0, \Cache\FileBeaconDependency::F(static::CACHE_BEAKON));
    }

    public static function reset_cache() {
        \Cache\FileBeaconDependency::F(static::CACHE_BEAKON)->reset_dependency_beacons();
    }

    public function t(string $query): string {
        if (!array_key_exists($query, $this->tokens)) {
            $this->tokens[$query] = $query;
            if (mb_strlen($query, 'UTF-8')) {
                \DB\DB::F()->exec("INSERT INTO chill__frontend__language(language_id,l,t)
                    SELECT id,:Pl,:Pl FROM language__language
                    ON DUPLICATE KEY UPDATE l=VALUES(l);", [":Pl" => $query]);
            }
        }
        return $this->tokens[$query];
    }

}
