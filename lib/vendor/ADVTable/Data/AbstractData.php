<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Data;

abstract class AbstractData implements IData {

    /** @var Array */
    protected $data = null;
    protected static $instance = null;

    protected final function __construct() {
        $this->singlet();
        $this->bind();
    }

    abstract protected function bind();

    protected final function singlet() {
        static::$instance = $this;
    }

    public function exists($n) {
        return array_key_exists($n, $this->data);
    }

    public function get($n, $def = null) {
        return $this->exists($n) ? $this->data[$n] : $def;
    }

    public function put($n, $v) {
        $this->data[$n] = $v;
        return $this;
    }

    public function remove($n) {
        if ($this->exists($n)) {
            unset($this->data[$n]);
        }
        return $this;
    }

    public function getPath($path, $def = null) {
        if ($this->pathExists($path)) {
            $pathParts = explode('.', $path);
            $c = &$this->data;
            for ($i = 0; $i < count($pathParts) - 1; $i++) {
                if (is_array($c) && is_array($c[$pathParts[$i]])) {
                    $c = &$c[$pathParts[$i]];
                    continue;
                }
                $c = null;
            }
            return is_array($c) && array_key_exists($pathParts[count($pathParts) - 1], $c) ? $c[$pathParts[count($pathParts) - 1]] : $def;
        }
        return $def;
    }

    public function pathExists($path) {
        $pathParts = explode('.', $path);
        $c = &$this->data;
        for ($i = 0; $i < count($pathParts) - 1; $i++) {
            if (is_array($c[$pathParts[$i]])) {
                $c = &$c[$pathParts[$i]];
                continue;
            }
            return false;
        }
        return is_array($c) && array_key_exists($pathParts[count($pathParts) - 1], $c) ? true : false;
    }

    //<editor-fold defaultstate="collapsed" desc="\ArrayAccess">
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

    //</editor-fold>

    /**
     * 
     * @return \Static
     */
    public final static function F() {
        return static::$instance ? static::$instance : new static();
    }

}
