<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile\Loader;

abstract class AbstractLoader {

    /**
     * @return ILoaderItem[]
     */
    abstract public function load(\Content\CatalogTile\CatalogTile $tile, \Content\CatalogTile\CatalogTileFull $full_tile=null): array;

    abstract protected static function get_loader_name(): string;

    abstract protected static function get_loader_description(): string;

    public static final function get_loader_info(): \Content\CatalogTile\LoaderInfo {
        return new \Content\CatalogTile\LoaderInfo(\Helpers\Helpers::ref_classs_to_root(get_called_class()), static::get_loader_name(), static::get_loader_description());
    }

    public static final function F(): AbstractLoader {
        return new static();
    }

}
