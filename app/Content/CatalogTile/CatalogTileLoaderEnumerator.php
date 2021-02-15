<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile;

/**
 * @property string $version
 * @property LoaderInfo[] $items
 */
class CatalogTileLoaderEnumerator implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    /** @var string */
    protected $version;

    /** @var LoaderInfo[] */
    protected $items;

    protected function __construct() {
        $this->version = static::get_file_version();
        $this->items = [];
        $this->load();
        $this->set_cache();
    }

    protected static function get_file_version() {
        return md5(implode("", [__FILE__, filemtime(__FILE__)]));
    }

    protected function load() {
        $this->items = [];
        $base_dir = __DIR__ . DIRECTORY_SEPARATOR . "Loader" . DIRECTORY_SEPARATOR;
        $files = scandir($base_dir);        
        foreach ($files as $file) {
            if (file_exists($base_dir . $file) && is_file($base_dir . $file) && is_readable($base_dir . $file)) {
                $m = [];
                if (preg_match("/Loader(?P<n>[^\.].{0,})\.php$/i", $file, $m)) {
                    $loaderClass = "\\".trim(__NAMESPACE__, "/\\") . "\\Loader\\Loader{$m['n']}";                    
                    if (class_exists($loaderClass) && \Helpers\Helpers::class_inherits($loaderClass, Loader\AbstractLoader::class)) {
                        $loader_info = $loaderClass::get_loader_info();                        
                        $loader_info && $loader_info->valid ? $this->items[$loader_info->name] = $loader_info : 0;
                    }
                }
            }
        }
    }

    protected function set_cache() {
        $cache = \Cache\FileCache::F();
        $cache->put(__CLASS__, $this, 0, \Cache\FileBeaconDependency::F(CatalogTile::CACHE_BEACON));
    }

    public static function F(): CatalogTileLoaderEnumerator {
        return new static();
    }

    public static function C(): CatalogTileLoaderEnumerator {
        $cache = \Cache\FileCache::F();
        $item = $cache->get(__CLASS__); /* @var $item self */
        $cs = self::class;
        if ($item && is_object($item) && ($item instanceof $cs) && $item->version === static::get_file_version()) {
            return $item;
        }
        return static::F();
    }
    
    
    public function get(string $name):LoaderInfo{
        return array_key_exists($name, $this->items)?$this->items[$name]:\Errors\common_error::RF("no loader class found for name `%s`",$name);
    }

    



    public function marshall() {
        return $this->t_default_marshaller_marshall_array(array_values($this->items));
    }

}
