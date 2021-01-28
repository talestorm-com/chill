<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile;

/**
 * @property string $class
 * @property string $name
 * @property string $info
 * @property bool $valid
 */
class LoaderInfo implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $class;

    /** @var string */
    protected $name;

    /** @var string */
    protected $info;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getrers">
    /** @return string */
    protected function __get__class() {
        return $this->class;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return bool */
    protected function __get__valid() {
        return ($this->class && class_exists($this->class) && $this->name && $this->info) ? true : false;
    }

    //</editor-fold>


    public function __construct(string $class = null, string $name = null, string $info = null) {
        $this->class = \Helpers\Helpers::NEString($class, null);
        $this->name = \Helpers\Helpers::NEString($name, null);
        $this->info = \Helpers\Helpers::NEString($info, null);
    }

    /**
     * 
     * @param string $class
     * @param string $name
     * @param string $info
     * @return \Content\CatalogTile\LoaderInfo
     */
    public static function F(string $class = null, string $name = null, string $info = null): LoaderInfo {
        return new static($class, $name, $info);
    }

    /**
     * 
     * @return \Content\CatalogTile\Loader\AbstractLoader
     */
    public function loader_instance(): Loader\AbstractLoader {
        $cs = $this->class;
        return $cs::F();
    }

}
