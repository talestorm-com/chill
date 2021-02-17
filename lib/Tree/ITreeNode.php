<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tree;

interface ITreeNode {

    public function is_valid(): bool;

    public function get_key(): string;

    public function get_parent_key();

    public function get_parent_id();

    public function is_root(): bool;

    public function get_sort_order(): int;

    public function get_node_id(): int;

    public function get_has_childs(): bool;

    public function get_node_level(): int;

    public function set_node_level(int $level = 0): ITreeNode;

    /**
     * only set parent object link if it matches parent_id
     * does not refactor tree
     */
    public function set_parent_node_object(ITreeNode $parent): ITreeNode;

    /**
     * add new child to node and refactor tree
     * (updates parents and paths)
     * @param \Tree\ITreeNode $child
     */
    public function add_child_node(ITreeNode $child): ITreeNode;

    /**
     * each node puts its key and instance into enumerator
     * @param array $enumerator
     */
    public function enumerate_keys(array &$enumerator);

    public function get_path(string $separator = "."): string;

    public function get_parent_path(string $separator = "."): string;

    public function get_node_name(): string;

    /**
     * @return ITreeNode|null
     */
    public function get_parent_node();

    /**
     * <b>true</b> if <b>$x</b> is a child of <b>$this</b> 
     * <b>false</b> otherwise
     * @param \Tree\ITreeNode $x
     */
    public function is_my_child(ITreeNode $x): bool;

    /**
     * <b>true</b> if <b>$x</b> is a ancestor of <b>$this</b> 
     * <b>false</b> otherwise
     * @param \Tree\ITreeNode $x
     */
    public function is_my_ancestor(ITreeNode $x): bool;

    public function enumerate_ids(array &$enumerator);

    /**
     * returns array of childs !BEWARE! - not a clone = direct array!
     */
    public function get_childs_array(): array;

    public function map(callable $callable, ITree $tree, $user_obj = null);
}
