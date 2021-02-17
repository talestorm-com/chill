<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tree;

interface ITree extends \common_accessors\IMarshall {

    /** instantiate and load tree */
    public static function F($arg = null): ITree;

    /** restore tree from cache if possible,otherwise - instantiate & load */
    public static function C($arg = null): ITree;

    public static function transform_arg_to_string($argument = null): string;

    public function get_class_cached_version(): string;

    public function reset_index(): ITree;

    public function import(array $nodes): ITree;

    public function get_node_instance(): ITreeNode;

    public function load(): ITree;

    public function set_cache(): ITree;

    public function get_cache_lifetime(): int;

    public function get_cache_dependency();

    /**
     * 
     * @param int $id
     * @param mixed $default
     * @return ITreeNode|null
     */
    public function get_item_by_id(int $id, $default = null);

    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return ITreeNode|null
     */
    public function get_item_by_key(string $key, $default = null);

    /**
     * <b>true</b> if <b>$of</b> in ancestors list of <b>$node</b> 
     * <b>false</b> othewise
     * @param \Tree\ITreeNode $node
     * @param \Tree\ITreeNode $of
     */
    public function is_node_a_child_of(ITreeNode $node, ITreeNode $of): bool;

    public function enum_childs_ids_of_id(int $id, bool $include_self = true): array;
    
    
    public function map(callable $callable,$user_obj=null);
}
