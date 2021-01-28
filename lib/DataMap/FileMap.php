<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

class FileMap implements \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator;

    /** @var UploadedFile */
    protected $items;

    /** @var FileMap */
    protected static $instance;

    protected function __construct() {
        static::$instance = $this;
        $this->items = [];
        $this->on_start();
    }

    protected function on_start() {
        foreach ($_FILES as $field_name => $field_data) {
            if (array_key_exists('error', $field_data)) {
                if (is_array($field_data['error'])) {
                    $this->create_array_of_files($field_name, $field_data);
                } else {
                    $this->create_one_file($field_name, $field_data);
                }
            }
        }
        if (strcasecmp(HeaderDataMap::F()->get_filtered("Content-Type", ["Strip", "Trim", "NEString", "DefaultEmptyString"]), "application/json") === 0) {
            $images = InputDataMap::F()->get_filtered("upload_images", ["ArrayOfNEString", "DefaultEmptyArray"]);
            if (count($images)) {
                foreach ($images as $image) {
                    $file = FakeUploadedFile::F($image);
                    if ($file && $file->valid) {
                        $this->items[] = $file;
                    }
                }
            }
        }
    }

    protected function create_one_file(string $field_name, array $field_data) {
        if ($field_data['error'] === UPLOAD_ERR_OK) {
            $file = UploadedFile::F($field_name, $field_data);

            if ($file && $file->valid) {
                $this->items[] = $file;
            }
        }
    }

    protected function create_array_of_files($field_name, $field_data) {

        foreach ($field_data['error'] as $key => $value) {
            if ($value === UPLOAD_ERR_OK) {
                $this->create_one_file($field_name, [
                    'name' => $field_data['name'][$key],
                    'type' => $field_data['type'][$key],
                    'size' => $field_data['size'][$key],
                    'tmp_name' => $field_data['tmp_name'][$key],
                    'error' => $field_data['error'][$key]
                ]);
            }
        }
    }

    /**
     * 
     * @param string $field_name
     * @return UploadedFile[]
     */
    public function get_by_field_name(string $field_name): Array {
        $r = [];
        foreach ($this->items as $item) {
            $item->field_name === $field_name ? $r[] = $item : false;
        }
        return $r;
    }

    /**
     * 
     * @param int $index
     * @return UploadedFile
     */
    public function get_by_index(int $index = 0) {
        return array_key_exists($index, $this->items) ? $this->items[$index] : null;
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

    /**
     * 
     * @param \DataMap\FakeUploadedFile $file
     * @return $this
     */
    public function add_fake(FakeUploadedFile $file) {
        if ($file->valid) {
            $this->items[] = $file;
        }
        return $this;
    }

}
