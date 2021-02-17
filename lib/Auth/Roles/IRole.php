<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth\Roles;

interface IRole {

    public static function F(): IRole;

    /**
     * checks when $x is an object of current role class
     * @param object $x
     */
    public static function is($x): bool;

    public function is_a(string $required_class): bool;
}
