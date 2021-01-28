<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * Description of ImageFlyContextHandler
 *
 * @author eve
 */
abstract class ImageFlyContextHandler {

    //put your code here

    abstract public static function on_source_not_found(string $source_path, string $context, string $owner_id, string $image_name): bool;
}
