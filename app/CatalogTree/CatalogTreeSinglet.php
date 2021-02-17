<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CatalogTree;

/**
 * @property CatalogTree $tree
 */
final class CatalogTreeSinglet {

    use \common_accessors\TCommonAccess;

    /** CatalogTreeSinglet */
    private static $instance;

    /** @var CatalogTree */
    private $tree;

    protected function __get__tree() {
        return $this->tree;
    }

    /**
     * 
     * @return \CatalogTree\CatalogTreeSinglet
     */
    public static function F(): CatalogTreeSinglet {
        return static::$instance ? static::$instance : new static();
    }

    private function __construct() {
        $this->tree = CatalogTree::C();
        static::$instance = $this;
    }

}
