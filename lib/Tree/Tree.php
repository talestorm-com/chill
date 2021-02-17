<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tree;

/**
 * @property string $version
 * @property string $cache_key
 */
abstract class Tree implements ITree {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    /** @var ITreeNode[] only root nodes */
    protected $root;
    /* @var ITreeNode[]   key=>value index of all nodes */
    protected $index;

    /** @var string */
    protected $version;
    protected $cache_key = null;
    protected $arg = null;

    protected function __get__version() {
        return $this->version;
    }

    protected function __get__cache_key() {
        return $this->cache_key;
    }

    protected function __construct($arg = null) {
        $this->root = [];
        $this->index = [];
        $this->version = static::get_class_version();
        $this->cache_key = static::transform_arg_to_string($arg);
        $this->arg = $arg;
    }

    protected static function get_class_version() {
        /* ver control должен включать в себя полный путь суперклассов */
        $b = [get_called_class(), filemtime(__FILE__)];
        $refClass = new \ReflectionClass(get_called_class());
        while ($refClass && $refClass->getName() !== __CLASS__) {
            $fn = $refClass->getFileName();
            if ($fn && file_exists($fn) && is_file($fn)) {
                $b[] = filemtime($fn);
            }
            $refClass = $refClass->getParentClass();
        }
        return md5(implode("-", $b));
    }

    public function get_node_instance(): ITreeNode {
        return TreeNode::F();
    }

    public function import(array $nodes): ITree {
        foreach ($nodes as $raw_node) {
            $node = $this->get_node_instance();
            $node->import($raw_node);
            if ($node && $node->is_valid()) {
                $this->index[$node->get_key()] = $node;
            }
        }
        uasort($this->index, function(ITreeNode $a, ITreeNode $b) {
            /* @var $a ITreeNode */
            /* @var $b ITreeNode */
            $r = $a->get_sort_order() - $b->get_sort_order();
            return $r === 0 ? ($a->get_node_id() - $b->get_node_id() ) : $r;
        });
        foreach ($this->index as $key => $value) {/** @var $value ITreeNode */
            if ($value->get_parent_key() === null) {
                $this->root[] = $value;
            } else if (array_key_exists($value->get_parent_key(), $this->index)) {
                $this->index[$value->get_parent_key()]->add_child_node($value);
            }
        }
        $this->on_after_import();
        return $this;
    }

    /** override */
    protected function on_after_import() {
        foreach ($this->root as $node) {
            $node->set_node_level();
        }
    }

    public function __sleep() {
        return ['root', 'version'];
    }

    public function __wakeup() {
        $this->reset_index();
    }

    public function reset_index(): ITree {
        $this->index = [];
        foreach ($this->root as $node) {
            $node->enumerate_keys($this->index);
        }
        return $this;
    }

    /**
     * 
     * @return \Tree\Tree
     */
    public static function F($arg = null): ITree {
        $x = new static($arg);
        return $x->load();
    }

    public static function C($arg = null): ITree {
        $cache_key = get_called_class() . static::transform_arg_to_string($arg);
        $test = \Cache\FileCache::F()->get($cache_key);
        $cu_class = static::class;
        if ($test && ($test instanceof $cu_class)) {
            if ($test->get_class_cached_version() === static::get_class_version()) {
                return $test;
            }
        }
        $r = static::F($arg);
        $r->set_cache();
        return $r;
    }

    public function set_cache(): ITree {
        $cache_key = get_called_class() . $this->cache_key;
        $cache = \Cache\FileCache::F();
        $cache->put($cache_key, $this, $this->get_cache_lifetime(), $this->get_cache_dependency());
        return $this;
    }

    public function get_cache_lifetime(): int {
        return 0;
    }

    /**
     * 
     * @return \Cache\ICacheDependency
     */
    public function get_cache_dependency() {
        return null;
    }

    public function get_class_cached_version(): string {
        return $this->version;
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->root);
    }

    /**
     * 
     * @param int $id
     * @param mixed $default
     * @return ITreeNode|null
     */
    public function get_item_by_id(int $id, $default = null) {
        $key = "N{$id}";
        return $this->get_item_by_key($key, $default);
    }

    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return ITreeNode|null
     */
    public function get_item_by_key(string $key, $default = null) {
        return array_key_exists($key, $this->index) ? $this->index[$key] : $default;
    }

    public function is_node_a_child_of(ITreeNode $node, ITreeNode $of): bool {
        return $node->is_my_ancestor($of);
    }

    public function enum_childs_ids_of_id(int $id, bool $include_self = true): array {
        $result = [];
        $node = $this->get_item_by_id($id);
        if ($node) {
            if ($include_self) {
                $node->enumerate_ids($result);
            } else {
                foreach ($node->get_childs_array() as $child) {/* @var $child ITreeNode */
                    $child->enumerate_ids($result);
                }
            }
        }
        return $result;
    }
    
    
    public function map(callable $callable,$user_obj=null) {
        foreach ($this->root as $node){
            call_user_func_array($callable, [$node,$this,$user_obj]);
            $node->map($callable,$this,$user_obj);
        }
    }

}
