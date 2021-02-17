<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content;

interface IImageCollection extends \common_accessors\IMarshall, \Iterator, \Countable {

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @return IImageCollection
     */
    public function load(string $context, string $owner_id): IImageCollection;

    /**
     * @return \ImageFly\ImageInfo[]
     */
    public function get_images_as_array(): array;

    /**
     * @return bool;
     */
    public function get_has_images(): bool;
      /**
     * @return bool;
     */
    public function get_empty(): bool;

    /**
     * @return int
     */
    public function get_images_count(): int;

    /**
     * 
     * @param int $index
     * @return \ImageFly\ImageInfo|null
     */
    public function get_image_by_index(int $index = 0);
}
