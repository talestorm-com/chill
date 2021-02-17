<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tree;

/**
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property ITreeNode[] $childs
 * @property ITreeNode $parent
 * @property string $parent_key
 * @property int $sort_order
 * @property int $parent_id
 * @property bool $valid
 * @property bool $has_childs
 * @property int $node_level
 */
class TreeNode implements ITreeNode, \Iterator, \Countable, \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TIterator,
        \common_accessors\TDefaultMarshaller;

    /** @var int */
    protected $id;

    /** @var string */
    protected $key;

    /** @var string */
    protected $name;

    /** @var ITreeNode[] */
    protected $childs;

    /** @var ITreeNode */
    protected $parent;

    /** @var int */
    protected $parent_id;

    /** @var string */
    protected $parent_key;

    /** @var int */
    protected $sort_order = 0;

    /** @var int */
    protected $node_level = 0;

    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return integer */
    protected function __get__id() {
        return $this->get_node_id();
    }

    /** @return string */
    protected function __get__name() {
        return $this->get_node_name();
    }

    /** @return ITreeNode[] */
    protected function __get__childs() {
        return $this->childs;
    }

    /** @return ITreeNode */
    protected function __get__parent() {
        return $this->get_parent_node();
    }

    /** @return string */
    protected function __get__key() {
        return $this->get_key();
    }

    protected function __get__parent_key() {
        return $this->get_parent_key();
    }

    protected function __get__sort_order() {
        return $this->get_sort_order();
    }

    protected function __get__valid() {
        return $this->is_valid();
    }

    protected function __get__parent_id() {
        return $this->get_parent_id();
    }

    protected function __get__has_childs() {
        return $this->get_has_childs();
    }

    protected function __get__node_level() {
        return $this->get_node_level();
    }

    //</editor-fold>

    protected function __construct() {
        $this->childs = [];
    }

    public function import(array $data): ITreeNode {
        $this->import_props($data);
        return $this;
    }

    protected function t_common_import_after_import() {
        $this->childs = [];
        $this->key = "N{$this->id}";
        $this->parent_key = $this->parent_id ? "N{$this->parent_id}" : null;
        return $this;
    }

    protected function t_common_import_get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'parent_id' => ['IntMore0', 'DefaultNull'],
            'sort_order' => ['AnyInt', 'Default0'],
        ];
    }

    /**
     * 
     * @return \Tree\ITreeNode
     */
    public static function F(): ITreeNode {
        return new static();
    }

    protected function t_iterator_get_internal_iterable_name() {
        return 'childs';
    }

    public function __sleep() {
        return ['id', 'name', 'childs', 'parent_id', 'sort_order', 'node_level'];
    }

    public function __wakeup() {
        $this->key = "N{$this->id}";
        $this->parent_key = $this->parent_id ? "N{$this->parent_id}" : null;
        foreach ($this->childs as $child) {
            $child->set_parent_node_object($this);
        }
    }

    public function get_key(): string {
        return $this->key;
    }

    public function get_parent_key() {
        return $this->parent_key;
    }

    public function get_sort_order(): int {
        return $this->sort_order;
    }

    public function is_root(): bool {
        return $this->parent_id === null ? true : false;
    }

    public function is_valid(): bool {
        return ($this->id && $this->name) ? true : false;
    }

    public function set_parent_node_object(ITreeNode $parent): ITreeNode {
        /** это для восстановления после сериализации */
        if (($parent->get_key() === $this->parent_key) && $this->parent_key) {
            $this->parent = $parent;
        }
        return $this;
    }

    public function add_child_node(ITreeNode $child): ITreeNode {
        $this->childs[] = $child;
        $child->set_parent_node_object($this);
        return $this; //рефакторинг ноды должно выполнить дерево. в конце концов это просто дампкеш
    }

    public function get_node_id(): int {
        return $this->id;
    }

    protected function t_default_marshaller_export_property_parent() {
        return null;
    }

    public function enumerate_keys(array &$enumerator) {
        $enumerator[$this->key] = $this;
        foreach ($this->childs as $child) {
            $child->enumerate_keys($enumerator);
        }
    }

    public function enumerate_ids(array &$enumerator) {
        $enumerator[] = $this->id;
        foreach ($this->childs as $child) {
            $child->enumerate_ids($enumerator);
        }
    }

    public function get_parent_path(string $separator = "."): string {
        $result = [];
        $c = $this->parent;
        while ($c) {
            $result[] = $c->get_node_name();
            $c = $c->get_parent_node();
        }
        return implode($separator, array_reverse($result));
    }

    public function get_path(string $separator = "."): string {
        $result = [];
        $c = $this;
        while ($c) {
            $result[] = $c->get_node_name();
            $c = $c->get_parent_node();
        }
        return implode($separator, array_reverse($result));
    }

    public function get_node_name(): string {
        return $this->name;
    }

    public function get_parent_node() {
        return $this->parent;
    }

    public function get_parent_id() {
        return $this->parent_id;
    }

    public function is_my_ancestor(ITreeNode $x): bool {
        $t = $this;
        while ($t) {
            if ($t === $x) {
                return true;
            }
            $t = $t->parent;
        }
        return false;
    }

    public function is_my_child(ITreeNode $x): bool {
        return $x->is_my_ancestor($this);
    }

    public function get_childs_array(): array {
        return $this->childs;
    }

    public function get_has_childs(): bool {
        return count($this->childs) ? true : false;
    }

    public function get_node_level(): int {
        return $this->node_level;
    }

    public function set_node_level(int $level = 0): ITreeNode {
        $this->node_level = $level;
        foreach ($this->childs as $child) {
            $child->set_node_level($level + 1);
        }
        return $this;
    }

    public function map(callable $callable, ITree $tree, $user_obj = null) {
        foreach ($this->childs as $child) {
            call_user_func_array($callable, [$child, $tree, $user_obj]);
            $child->map($callable, $tree, $user_obj);
        }
    }

}
