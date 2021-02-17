<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\CatalogSizeDef;

/**
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @property bool $valid
 * @property bool $visible
 * @property string $info
 */
class CatalogSizeDefinition implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $short_name;

    /** @var bool */
    protected $visible;

    /** @var string */
    protected $info;

    protected function __get__id() {
        return $this->id;
    }

    protected function __get__name() {
        return $this->name;
    }

    protected function __get__short_name() {
        return $this->short_name;
    }

    protected function __get__visible() {
        return $this->visible;
    }

    protected function __get__info() {
        return $this->info;
    }

    protected function __get__valid() {
        return ($this->id && $this->name && $this->short_name) ? true : false;
    }

    protected function __construct(array $data = null) {
        if ($data) {
            $this->import_props($data);
        }
    }

    /**
     * 
     * @param array $data
     * @return \DataModel\CatalogSizeDef\CatalogSizeDefinition
     */
    public static function F(array $data = null): CatalogSizeDefinition {
        return new static($data);
    }

    protected function t_common_import_get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'short_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'visible' => ['Boolean', 'DefaultTrue'],
            'info' => ['Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
