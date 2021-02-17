<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers;

/**
 * NSProps interface
 */
interface INSProps {

    /**
     * returns default controller class name for this namespace
     * @return string
     */
    public function get_default_controller(): string;

    /**
     * returns default controller action
     * @return string
     */
    public function get_default_action(): string;

    public static function F(): INSProps;
}
