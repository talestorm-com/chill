<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile\Loader;

class LoaderDefault extends AbstractLoader {

    protected static function get_loader_description(): string {
        return "Каталоги без дочерних";
    }

    protected static function get_loader_name(): string {
        return "Default";
    }
    public function load(\Content\CatalogTile\CatalogTile $tile, \Content\CatalogTile\CatalogTileFull $full_tile = null):array {        
        // nothing to load!
        $result = [];
        foreach($tile->catalogs as $catalog){
            $result[]=$catalog;
        }        
        return $result;
    }

}
