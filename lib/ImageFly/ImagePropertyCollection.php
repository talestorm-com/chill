<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * @property string $context
 * @property string $image
 * @property string $owner_id
 */
class ImagePropertyCollection implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var array */
    protected $items;

    /** @var string */
    protected $context;

    /** @var string */
    protected $owner_id;

    /** @var string */
    protected $image;

    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__context() {
        return $this->context;
    }

    /** @return string */
    protected function __get__image() {
        return $this->image;
    }

    /** @return string */
    protected function __get__owner_id() {
        return $this->owner_id;
    }

    //</editor-fold>

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @param string $image
     */
    public function __construct(string $context, string $owner_id, string $image) {
        $this->items = [];
        $this->context = $context;
        $this->owner_id = $owner_id;
        $this->image = $image;
    }

    public function import_array(array $kva, $mode = null): ImagePropertyCollection {
        $kvaf = \Filters\FilterManager::F()->apply_filter_array($kva, $this->get_filters($mode), $this->get_filter_params($mode));
        $this->items = $kvaf;
        return $this;
    }

    public function import_array_for_append(array $kva) {
        $kvaf = \Filters\FilterManager::F()->apply_filter_array($kva, $this->get_filters(ImagePropertiesFilterDelegate::MODE_APPEND), $this->get_filter_params(ImagePropertiesFilterDelegate::MODE_APPEND));
        $this->items = [];
        foreach ($kvaf as $key => $value) {
            if (!\Filters\Value::is($value)) {
                if (array_key_exists($key, $kva)) {
                    $this->items[$key] = $value;
                }
            }
        }
        return $this;
    }

    public function import_datamap(\DataMap\IDataMap $kvm, $mode = null): ImagePropertyCollection {
        $kvaf = \Filters\FilterManager::F()->apply_filter_datamap($kvm, $this->get_filters($mode), $this->get_filter_params($mode));
        $this->items = $kvaf;
        return $this;
    }

    public function import_json(string $json, $mode = null): ImagePropertyCollection {
        return $this->import_array(json_decode($json, TRUE), $mode);
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @param string $image
     * @return \ImageFly\ImagePropertyCollection
     */
    public static function F(string $context, string $owner_id, string $image): ImagePropertyCollection {
        return new static($context, $owner_id, $image);
    }

    protected function get_filters($mode = null) {
        $ns = "\\" . trim(__NAMESPACE__, "\\") . "\\";
        $context_name = ucfirst($this->context);
        $class_name = "{$ns}ImagePropertiesFilterContext{$context_name}";
        if (class_exists($class_name) && \Helpers\Helpers::class_implements($class_name, ImagePropertiesFilterDelegate::class)) {
            $delegate = $class_name::F(); /* @var $delegate ImagePropertiesFilterDelegate */
            return $delegate->get_filters($mode);
        }
        return DefaultPropertiesFilterDelegate::F()->get_filters($mode);
    }

    protected function get_filter_params($mode = null) {
        $ns = "\\" . trim(__NAMESPACE__, "\\") . "\\";
        $context_name = ucfirst($this->context);
        $class_name = "{$ns}ImagePropertiesFilterContext{$context_name}";
        if (class_exists($class_name) && \Helpers\Helpers::class_implements($class_name, ImagePropertiesFilterDelegate::class)) {
            $delegate = $class_name::F(); /* @var $delegate ImagePropertiesFilterDelegate */
            return $delegate->get_filters_params($mode);
        }
        return DefaultPropertiesFilterDelegate::F()->get_filters_params($mode);
    }

}
