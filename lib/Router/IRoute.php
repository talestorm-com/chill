<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Router;

interface IRoute {

    public function get_namespace(): string;

    public function get_controller(): string;

    public function get_action(): string;

    public function get_params(): \DataMap\IDataMap;

    public function get_controller_class(): string;

    public function get_controller_namespace(): string;

    public function get_route_is_valid(): bool;
}
