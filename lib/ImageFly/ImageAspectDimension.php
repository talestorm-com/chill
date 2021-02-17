<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * Description of ImageAspectDimension
 *
 * @author eve
 * @property string $preset
 * @property double $csx
 * @property double $csy
 * @property double $cex
 * @property double $cey
 * @property boolean $valid
 */
class ImageAspectDimension implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $preset;

    /** @var double */
    protected $csx;

    /** @var double */
    protected $csy;

    /** @var double */
    protected $cex;

    /** @var double */
    protected $cey;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__context() {
        return $this->context;
    }

    /** @return string */
    protected function __get__owner_id() {
        return $this->owner_id;
    }

    /** @return string */
    protected function __get__image() {
        return $this->image;
    }

    /** @return string */
    protected function __get__preset() {
        return $this->preset;
    }

    /** @return double */
    protected function __get__csx() {
        return $this->csx;
    }

    /** @return double */
    protected function __get__csy() {
        return $this->csy;
    }

    /** @return double */
    protected function __get__cex() {
        return $this->cex;
    }

    /** @return double */
    protected function __get__cey() {
        return $this->cey;
    }

    /** @return boolean */
    protected function __get__valid() {
        return $this->preset && $this->csx !== null && $this->csy !== null && $this->cex !== null && $this->cey !== null;
    }

    //</editor-fold>

    public function __construct(array $data) {
        $this->import_props($data);
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data) {
        return new static($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'preset' => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            'csx' => ["Float", "DefaultNull"], //double
            'csy' => ["Float", "DefaultNull"], //double
            'cex' => ["Float", "DefaultNull"], //double
            'cey' => ["Float", "DefaultNull"], //double
        ];
    }

}
