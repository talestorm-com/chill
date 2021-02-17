<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile;

/**
 * @property CatalogTile $tile
 * @property ITileItem[] $items
 * @property string $version
 * @property bool $dealer
 */
class CatalogTileFull extends \Content\Content {

    /** @var CatalogTile */
    protected $tile;

    /** @var ITileItem[] */
    protected $items;
    protected $version;

    /** @var bool */
    protected $dealer;

    /** @return CatalogTile */
    protected function __get__tile() {
        return $this->tile;
    }

    protected function __get__dealer() {
        return $this->dealer;
    }

    /** @return ITileItem[] */
    protected function __get__items() {
        return $this->items;
    }

    protected function __get__version() {
        return $this->version;
    }

    protected function get_template_file_name($template = null) {
        return \Content\ContentViewResolver::F()->resolve_path_for_object($this->tile, $template);
    }

    protected function __construct(string $alias) {
        $this->tile = CatalogTile::FA($alias);
        $loader = CatalogTileLoaderEnumerator::C()->get($this->tile->loader)->loader_instance();
        $this->dealer = static::is_dealer_view();
        $this->items = $loader->load($this->tile,$this);
        $this->version = static::get_file_ver();
        
        $cache = \Cache\FileCache::F();
        $cache->put(static::cache_key($this->tile->alias), $this, 0, \Cache\FileBeaconDependency::F(implode(",", [CatalogTile::CACHE_BEACON, "front_catalog", "product"])));
    }

    protected static function get_file_ver() {
        return md5(implode("", [__FILE__, filemtime(__FILE__)]));
    }

    /**
     * 
     * @param string $alias
     * @return \Content\CatalogTile\CatalogTileFull
     */
    public static function F(string $alias): CatalogTileFull {
        return new static($alias);
    }

    protected static function is_dealer_view(): bool {
        if (\Auth\Auth::F()->is_authentificated()) {
            if (\Auth\Auth::F()->get_user_info()->is_dealer) {
                if (\Auth\Auth::F()->is(\Auth\Roles\RoleDealer::class)) {
                    return true;
                }
            }
        }
        return false;
    }

    protected static function cache_key(string $alias): string {
        return implode("|", [__CLASS__, $alias, static::is_dealer_view() ? 'G' : 'R']);
    }

    public static function C(string $alias): CatalogTileFull {
        $cache_key = static::cache_key($alias);
        $cache = \Cache\FileCache::F();
        $item = $cache->get($cache_key); /* @var $item self */
        $cs = static::class;
        if ($item && is_object($item) && ($item instanceof $cs) && $item->version === static::get_file_ver()) {
            return $item;
        }
        return static::F($alias);
    }

}
