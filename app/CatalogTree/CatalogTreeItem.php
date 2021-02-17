<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CatalogTree;

/**
 * @property string $alias
 * @property bool $visible
 * @property string $guid
 * @property bool $html_mode
 * @property string $default_image
 * @property bool $has_visible_childs
 * @property string[] $import_processor
 * @property bool $terminal
 * @property bool $visible_parents  нода и все ее родители - видимые
 
 */
class CatalogTreeItem extends \Tree\TreeNode {

    /** @var string */
    protected $alias;

    /** @var bool */
    protected $visible;

    /** @var string */
    protected $guid;

    /** @var bool */
    protected $html_mode;

    /** @var string[] */
    protected $import_processor;

    /** @var string */
    protected $default_image;

    /** @var bool */
    protected $terminal;

    
    
    protected function __get__alias() {
        return $this->alias;
    }

    protected function __get__visible() {
        return $this->visible;
    }

    protected function __get__guid() {
        return $this->guid;
    }

    protected function __get__html_mode() {
        return $this->html_mode;
    }

    protected function __get__default_image() {
        return $this->default_image;
    }

    protected function __get__terminal() {
        return $this->terminal;
    }


    protected function __get__has_visible_childs() {
        $r = 0;
        foreach ($this->childs as $child) {/* @var $child self */
            $child->visible ? $r++ : 0;
        }
        return $r ? true : false;
    }

    protected function __get__import_processor() {
        return is_array($this->import_processor) ? $this->import_processor : [];
    }

    protected function __get__visible_parents() {
        $t = $this;
        while ($t) {
            if (!$t->visible) {
                return false;
            }
            $t = $t->parent;
        }
        return true;
    }

    protected function t_common_import_get_filters() {
        return array_merge(parent::t_common_import_get_filters(), [
            'alias' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'guid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'visible' => ['Boolean', 'DefaultFalse'],
            'html_mode' => ['Boolean', 'DefaultTrue'],
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'import_processor' => ['Strip', 'Trim', 'NEString', 'CSVArray', 'ArrayOfStrippedNEString', "NEArray", 'DefaultEmptyArray'],
            'terminal' => ['Boolean', 'DefaultFalse'],
            
        ]);
    }

    public function __sleep() {
        return array_merge(parent::__sleep(), ['alias', 'visible', 'guid', 'html_mode',
            'default_image', 'import_processor', 'terminal',
           ]);
    }

    /**
     * enumerates guids in out
     * @param array $out
     */
    public function rebuild_guid(array &$out, $guid_deep = 0) {
        if ($this->guid) {
            if (!array_key_exists($this->guid, $out)) {
                $out[$this->guid] = $this;
            }
        }
        foreach ($this->childs as $child) {/* @var $child CatalogTreeItem */
            $child->rebuild_guid($out, $guid_deep + 1);
        }
    }

    /**
     * enumerates aliases in out
     * @param array $out
     */
    public function rebuild_alias(array &$out, $guid_deep = 0) {
        if ($this->alias) {
            if (!array_key_exists($this->alias, $out)) {
                $out[$this->alias] = $this;
            }
        }
        foreach ($this->childs as $child) {/* @var $child CatalogTreeItem */
            $child->rebuild_alias($out, $guid_deep + 1);
        }
    }

}
