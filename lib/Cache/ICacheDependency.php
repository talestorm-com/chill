<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cache;

interface ICacheDependency {

    /**
     * returns current (not cached) dependency value.
     */
    public function get_dependency_current_value(): string;

    /**
     * returns internal stored dependency value to compare
     */
    public function get_stored_dependency_value(): string;

    /**
     * cache class call this just before caching
     * dependency must keep beacon value inside. dependency will be serialized with
     * cached data
     */
    public function store_dependency_value(): ICacheDependency;
    
    
    /**
     * checks when dependency is valid
     */
    public function is_valid():bool;
}
