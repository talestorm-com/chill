<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

interface IAuth {

    /** return true is current user is valid user (not a guest) */
    public function is_authentificated(): bool;

    /** checks when current role is or descedant of $role_class */
    public function is(string $role_class): bool;

    /** returns current role object */
    public function get_role(): \Auth\Roles\IRole;

    /** returns current user id or 0 if no current user */
    public function get_id(): int;

    /** returns current role class prefix */
    public function get_role_string(): string;

    /** returns curent user info or stub if no current user */
    public function get_user_info(): UserInfo;

    public function login(string $user, string $password): bool;
    public function login_phone(string $phone, string $password): bool;

    public function logout();

    public function force_login(int $user_id);

    public function force_login_s(string $user);
}
