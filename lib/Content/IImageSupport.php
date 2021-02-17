<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content;

interface IImageSupport {

    public function get_object_images(): IImageCollection;

    public function get_images_count(): int;

    public function get_has_images(): bool;
}
