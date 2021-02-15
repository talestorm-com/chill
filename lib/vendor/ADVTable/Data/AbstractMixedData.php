<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Data;

abstract class AbstractMixedData implements IData {

    /** @var AbstractMixedData */
    protected static $instance;

    /** @var AbstractData */
    protected $p1;

    /** @var AbstractData */
    protected $p2;

    private final function __construct() {
        static::$instance = $this;
        $this->bind();
    }

    protected abstract function bind();

    //<editor-fold defaultstate="collapsed" desc="IData">
    public function get($n, $def = null) {
        return $this->p1->exists($n) ? $this->p1->get($n, $def) : $this->p2->exists($n) ? $this->p2->get($n, $def) : $def;
    }

    public function exists($n) {
        return ($this->p1->exists($n) || $this->p2->exists($n));
    }

    public function put($n, $v) {
        $this->p1->put($n, $v);
        $this->p2->put($n, $v);
        return $this;
    }

    public function remove($n) {
        $this->p1->remove($n);
        $this->p2->remove($n);
        return $this;
    }

    public function offsetExists($offset) {
        return $this->exists($offset);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value) {
        return $this->put($offset, $value);
    }

    public function offsetUnset($offset) {
        return $this->remove($offset);
    }

    public function getPath($path, $def = null) {
        return $this->p1->pathExists($path) ? $this->p1->getPath($path, $def) : $this->p2->pathExists($path) ? $this->p2->getPath($path, $def) : $def;
    }

    public function pathExists($path) {
        return ($this->p1->pathExists($path) || $this->p2->pathExists($path));
    }

    //</editor-fold>

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

}
