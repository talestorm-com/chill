<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content;

/**
 * 
 * @property bool $empty
 * @property bool $has_images
 * 
 */
class DefaultImageCollection implements IImageCollection {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var \ImageFly\ImageInfo */
    protected $images;

    protected function __get__empty() {
        return !($this->get_has_images());
    }

    protected function __get__has_images() {
        return $this->get_has_images();
    }

    /**
     * 
     * @param int $index
     * @return \ImageFly\ImageInfo|null
     */
    public function get_image_by_index(int $index = 0) {
        return array_key_exists($index, $this->images) ? $this->images[$index] : 0;
    }

    /**
     * 
     * @return \ImageFly\ImageInfo[]
     */
    public function get_images_as_array(): array {
        return unserialize(serialize($this->images));
    }

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @return \Content\IImageCollection
     */
    public function load(string $context, string $owner_id): IImageCollection {
        $this->images = \ImageFly\ImageInfoManager::F()->list_images($context, $owner_id);
        return $this;
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->images);
    }

    protected function t_iterator_get_internal_iterable_name() {
        return 'images';
    }

    public function get_has_images(): bool {
        return count($this->images) ? true : false;
    }

    public function get_images_count(): int {
        return count($this->images);
    }

    public function get_empty(): bool {
        return !($this->get_has_images());
    }

    protected function __construct(string $context = null, string $owner_id = null) {
        $this->images = [];
        if ($context && $owner_id) {
            $this->load($context, $owner_id);
        }
    }

    public static function F(string $context = null, string $owner_id = null): IImageCollection {
        return new static($context, $owner_id);
    }

}
