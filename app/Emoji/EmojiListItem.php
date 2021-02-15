<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Emoji;

/**
 * Description of EmojiListItem
 *
 * @author eve
 * @property int $id
 * @property string $tag
 * @property string $image
 * @property int $sort
 * @property string[] $name
 * @property bool $valid
 */
class EmojiListItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    private static $_class_version;

    public static function get_class_version() {
        if (!static::$_class_version) {
            static::$_class_version = md5(implode("-", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_class_version;
    }

    //<editor-fold defaultstate="collapsed" desc="props">    

    /** @var int */
    protected $id;

    /** @var string */
    protected $tag;

    /** @var string */
    protected $image;

    /** @var int */
    protected $sort;

    /** @var string[] */
    protected $name;


    /** @var string */
    protected $meta_title;

    /** @var string */
    protected $meta_description;

    /** @var string */
    protected $additional_content;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__tag() {
        return $this->tag;
    }

    /** @return string */
    protected function __get__image() {
        return $this->image;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return string[] */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__meta_title() {
        return $this->meta_title;
    }
    /** @return string */
    protected function __get__meta_description() {
        return $this->meta_description;
    }
    /** @return string */
    protected function __get__additional_content() {
        return $this->additional_content;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->tag  && is_array($this->name) ;
    }

    //</editor-fold>


    public function __construct() {
        ;
    }

    public static function F() {
        return new static();
    }

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function load_db(int $id) {
        $query = "SELECT * FROM media__emoji WHERE id=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $this->import_props(is_array($row) ? $row : []);
        $this->load_names_db();
        return $this;
    }

    /**
     * 
     * @param array $data
     * @return $this
     */
    public function load_array(array $data) {
        $this->import_props($data);
        $this->name = [];
        return $this;
    }

    /**
     * for internal loader
     * @param string $language_id
     * @param string $name
     * @return $this
     */
    public function add_name(string $language_id, string $name) {
        $this->name[$language_id] = $name;
        return $this;
    }

    protected function load_names_db() {
        if ($this->id) {
            $this->name = [];
            $rows = \DB\DB::F()->queryAll("SELECT language_id,name FROM media__emoji__strings WHERE id=:P", [":P" => $this->id]);
            foreach ($rows as $row) {
                try {
                    $crow = \Filters\FilterManager::F()->apply_filter_array($row, ['language_id' => ['Strip', 'Trim', 'NEString'], 'name' => ['Strip', 'Trim', 'NEString']]);
                    \Filters\FilterManager::F()->raise_array_error($crow);
                    $this->name[$crow["language_id"]] = $crow["name"];
                } catch (\Throwable $e) {
                    
                }
            }
        }
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'],
            'tag' => ['Strip', 'Trim', 'NEString'],
            'sort' => ['Int', 'Default0'],
            'name' => ['NEArray', 'DefaultEmptyArray'],
            'image' => ['Trim', 'NEString','DefaultEmptyString'],
            'meta_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'meta_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'additional_content' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']
        ];
    }

}
