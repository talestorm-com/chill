<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\common_classes;

/**
 * @property string $text
 * @property string $link
 * @property bool $has_link
 * @property bool $valid
 */
class Breadcrumb implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props && accessors">
    /** @var string */
    protected $text;

    /** @var string */
    protected $link;

    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__text() {
        return $this->text;
    }

    /** @return string */
    protected function __get__link() {
        return $this->link;
    }

    /** @return bool */
    protected function __get__has_link() {
        return $this->link ? true : false;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->text ? true : false;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="setters">
    /** @param string $text */
    protected function __set__text($text) {
        $this->text = $text;
    }

    /** @param string $link */
    protected function __set__link($link) {
        $this->link = $link;
    }

    //</editor-fold>
    //</editor-fold>


    public function __construct(string $text, string $link = null) {
        $this->text = \Helpers\Helpers::NEString($text, null);
        $this->link = \Helpers\Helpers::NEString($link, null);
    }

    public static function F(string $text, string $link = null) {
        return new static($text, $link);
    }

}
