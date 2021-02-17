<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile;

/**
 * @property string $version
 * @property CatalogTileTemplatesTemplateInfo[] $items
 */
final class CatalogTileTemplatesEnumerator implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    /** @var CatalogTileTemplatesTemplateInfo[] */
    protected $items;

    /** @var string */
    protected $version;

    protected function __construct() {
        $this->version = static::get_file_version();
        $this->load();
        $this->set_cache();
    }

    protected function set_cache() {
        $cache = \Cache\FileCache::F();
        $cache->put(__CLASS__, $this, 0, \Cache\FileBeaconDependency::F(CatalogTile::CACHE_BEACON)); //CatalogTile::CACHE_BEACON
    }

    protected static function get_file_version() {
        return md5(implode("", [__FILE__, filemtime(__FILE__)]));
    }

    protected function load() {
        $dir = \Content\ContentViewResolver::F()->get_templates_dir_for_class(CatalogTile::class, null);
        $list = scandir($dir);
        $this->items = [];
        foreach ($list as $file_name) {
            if (file_exists("{$dir}{$file_name}") && is_file("{$dir}{$file_name}") && is_readable("{$dir}{$file_name}")) {
                $m = [];
                if (preg_match("/^(?P<t>[^\.].{0,})\.tpl$/i", $file_name, $m)) {
                    $item = CatalogTileTemplatesTemplateInfo::F($dir, $file_name, $m['t']);
                    $item && $item->valid ? $this->items[$item->name] = $item : 0;
                }
            }
        }
    }

    /**
     * load, no use cache, overwrite cache
     * @return \Content\CatalogTile\CatalogTileTemplatesEnumerator
     */
    public static function F(): CatalogTileTemplatesEnumerator {
        return new static();
    }

    /**
     * try load from cache, if cache is nonvalid - load and set cache
     */
    public static function C(): CatalogTileTemplatesEnumerator {
        $cache = \Cache\FileCache::F();
        $item = $cache->get(__CLASS__); /* @var $item self */
        $cs = self::class;
        if ($item & is_object($item) && ($item instanceof $cs) && $item->version === static::get_file_version()) {
            return $item;
        }
        return static::F();
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array(array_values($this->items));
    }

}
