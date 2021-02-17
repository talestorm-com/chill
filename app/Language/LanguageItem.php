<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of LanguageItem
 *
 * @author eve
 * @property string $id
 * @property string $name_en
 * @property string $name
 * @property boolean $enabled
 * @property int $sort
 */
class LanguageItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    private static $_class_version = null;

    public static function get_class_version() {
        if (!static::$_class_version) {
            static::$_class_version = md5(implode("-", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_class_version;
    }

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $id;

    /** @var string */
    protected $name_en;

    /** @var string */
    protected $name;

    /** @var boolean */
    protected $enabled;

    /** @var int */
    protected $sort;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__name_en() {
        return $this->name_en;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return boolean */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    //</editor-fold>


    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['Strip', 'Trim', 'NEString'], //string
            'name_en' => ['Strip', 'Trim', 'NEString'], //string
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'enabled' => ['Boolean', 'DefaultTrue'], //boolean
            'sort' => ['Int', 'Default0'], //int
        ];
    }

    public function __construct() {
        
    }

    public function load_array(array $data) {
        $this->import_props($data);
        return $this;
    }

    /**
     * 
     * @return \Language\LanguageItem
     */
    public static function F(): LanguageItem {
        return new static ();
    }

    public function __toString() {
        return $this->id;
    }

}
