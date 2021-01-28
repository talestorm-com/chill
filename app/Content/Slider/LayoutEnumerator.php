<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Slider;

/**
 * @property string $version
 */
class LayoutEnumerator implements \common_accessors\IMarshall {  

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    protected static $instance; 
    protected $layouts;
    protected $version;

    protected function __get__version() {
        return $this->version;
    }

    protected static function get_file_version() {
        return md5(implode("", [__FILE__, filemtime(__FILE__)]));
    }

    protected function __construct() {
        $this->version = static::get_file_version();
        $this->refresh_layouts();
    }

    public function refresh_layouts() {
        $this->load();
        $this->set_cache();
    }

    protected static function cache_key() {
        return __METHOD__;
    }

    public function get_layouts_dir() {
        $dir = \Content\ContentViewResolver::F()->get_templates_dir_for_class(Slider::class,"layouts");
        //$dir = \Config\Config::F()->VIEW_PATH . "modules" . DIRECTORY_SEPARATOR . "slider" . DIRECTORY_SEPARATOR . "layouts";
        if (!(file_exists($dir) && is_dir($dir) && is_readable($dir))) {
            mkdir($dir, 0777, true);
        }
        if (!(file_exists($dir) && is_dir($dir) && is_readable($dir))) {
            \Errors\common_error::RF("cant access layouts dir `%s` in `%s`", $dir, __METHOD__);
        }
        return $dir . DIRECTORY_SEPARATOR;
    }

    protected function load() {
        $dir = $this->get_layouts_dir();
        $list = scandir($dir);
        $this->layouts = [];
        foreach ($list as $file_name) {
            $path = "{$dir}{$file_name}";
            if (is_file($path) && is_readable($path)) {
                $m = [];
                if (preg_match("/^layout_(?P<n>.*)\.tpl/i", $file_name, $m)) {
                    $n = $m['n'];
                    $nfp = "{$dir}layout_{$n}.nfo";
                    $info = $n;
                    if (file_exists($nfp) && is_file($nfp) && is_readable($nfp)) {
                        $info = file_get_contents($nfp);
                    }
                    $this->layouts[] = ['name' => $n, 'title' => $info];
                }
            }
        }
    }

    protected function set_cache() {
        $key = static::cache_key();
        $cache = \Cache\FileCache::F();
        $cache->put($key, $this, 0);
    }

    /**
     * 
     * @return \Content\Slider\LayoutEnumerator
     */
    public static function F(): LayoutEnumerator {
        return static::$instance ? static::$instance : static::factory();
    }

    /**
     * 
     * @return \Content\Slider\LayoutEnumerator
     */
    public static function C(): LayoutEnumerator {
        return static::F();
    }

    protected function factory() {
        $cache = \Cache\FileCache::F();
        $key = static::cache_key();
        $some = $cache->get($key); /* @var $some self */
        $cs = self::class;
        if ($some && is_object($some) && ($some instanceof $cs) && $some->version === static::get_file_version()) {
            return $some;
        }
        return new static();
    }

    public function marshall() { 
        return $this->t_default_marshaller_marshall_array($this->layouts);
    }

}
