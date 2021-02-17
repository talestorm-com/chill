<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MenuTree;

/**
 * @property string $url
 * @property bool $visible
 * @property bool $url_ok
 * @property string $css_class
 * @property bool $is_content_block
 * @property string $content_block_alias
 * @property bool $is_catalog_tree
 * @property string $root_tree_alias
 */
class MenuTreeItem extends \Tree\TreeNode {

    /** @var string */
    protected $url;

    /** @var bool */
    protected $visible;

    /** @var string */
    protected $css_class;

    protected function __get__url() {
        return $this->url;
    }

    protected function __get__visible() {
        return $this->visible;
    }

    protected function __get__css_class() {
        return $this->css_class;
    }

    protected function __get__url_ok() {
        return $this->url && mb_strlen($this->url, 'UTF-8') > 1 ? true : false;
    }

    /**
     * 
     * @return \CatalogTree\CatalogTreeItem
     */
    public function get_catalog() {
        $x = $this->__get__root_tree_alias();
        if ($x) {
            $tree = \CatalogTree\CatalogTreeSinglet::F()->tree; /* @var $tree \CatalogTree\CatalogTree */
            return $tree->get_item_by_alias($x, null);
        }
        return null;
    }

    protected function __get__is_content_block() {
        return $this->url && preg_match("/^contentblock:\/\/.{1,}$/i", $this->url) ? true : FALSE;
    }

    protected function __get__content_block_alias() {
        $m = [];
        if ($this->url && preg_match("/^contentblock:\/\/(?P<a>.{1,})$/i", $this->url, $m)) {
            return $m['a'];
        }
        return null;
    }

    protected function __get__is_catalog_tree() {
        return $this->url && preg_match("/^catalog:\/\/.{1,}$/i", $this->url) ? true : FALSE;
    }

    protected function __get__root_tree_alias() {
        $m = [];
        if ($this->url && preg_match("/^catalog:\/\/(?P<a>.{1,})$/i", $this->url, $m)) {
            return $m['a'];
        }
        return null;
    }

    protected function t_common_import_get_filters() {
        return array_merge(parent::t_common_import_get_filters(), [
            'url' => ['Trim', 'NEString', 'DefaultNull'],
            'visible' => ['Boolean', 'DefaultFalse'],
            'css_class' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ]);
    }

    public function __sleep() {
        return array_merge(parent::__sleep(), ['url', 'visible', 'css_class']);
    }

}
