<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent;

/**
 * Description of MediaContentType
 *
 * @author eve
 * @property string $type
 * @property string $table_alias
 * @property string $name
 * @property string $table
 * @property string[] $columns
 * @property string $editor
 * @property bool $visible
 */
class MediaContentType implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    private static $class_version;
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $type;

    /** @var string */
    protected $name;

    /** @var string */
    protected $table;
    protected $table_alias;

    /** @var String[] */
    protected $columns;
    protected $editor;
    protected $visible;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__type() {
        return $this->type;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__table() {
        return $this->table;
    }

    /** @return String[] */
    protected function __get__columns() {
        return $this->columns;
    }

    protected function __get__table_alias() {
        return $this->table_alias;
    }

    protected function __get__editor() {
        return $this->editor;
    }

    protected function __get__visible() {
        return $this->visible;
    }

    //</editor-fold>
    protected function __construct(array $data) {
        $this->import_props($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'type' => ["Strip", "Trim", "NEString"],
            'table_alias' => ["Strip", "Trim", "NEString"],
            'name' => ['Strip', 'Trim', 'NEString'],
            'visible' => ['Boolean', 'DefaultTrue',],
            'table' => ['Strip', 'Trim', 'NEString'],
            'editor' => ['Strip', 'Trim', 'NEString'],
            'columns' => ['NEArray', 'DefaultEmptyArray'],
        ];
    }

    protected function t_common_import_after_import() {
        $nc = [];
        foreach ($this->columns as $column) {
            $column = \Filters\FilterManager::F()->apply_chain($column, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $column ? $nc[] = $column : 0;
        }
        $this->columns = $nc;
    }

    /**
     * 
     * @param array $data
     * @return \Content\MediaContent\MediaContentType
     */
    public static function F(array $data): MediaContentType {
        return new static($data);
    }

    public static function get_class_version() {
        if (!static::$class_version) {
            static::$class_version = implode("|", [__FILE__, filemtime(__FILE__)]);
        }
        return static::$class_version;
    }

}
