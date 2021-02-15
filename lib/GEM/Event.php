<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GEM;

/**
 * @property string $message
 * @property string $class
 * @property string $method
 * @property string $key
 */
class Event {

    use \common_accessors\TCommonAccess;

    /** @var string */
    protected $method;

    /** @var string */
    protected $class;

    /** @var string */
    protected $key;

    /** @var string */
    protected $message;

    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__message() {
        return $this->message;
    }

    /** @return string */
    protected function __get__class() {
        return $this->class;
    }

    /** @return string */
    protected function __get__method() {
        return $this->method;
    }

    /** @return string */
    protected function __get__key() {
        return $this->key;
    }

    //</editor-fold>

    protected function __construct(string $message, string $class, string $method) {
        $this->message = trim(mb_strtoupper($message, "UTF-8"));
        $this->class = $class;
        $this->method = $method;
        $this->key = static::mk_event_key($this->message, $this->class, $this->method);
    }

    public function run(EventKVS $params = null) {
        if (\Helpers\Helpers::class_exists($this->class)) {
            $ca = [$this->class, $this->method];
            if (is_callable($ca)) {
                try {
                    //call_user_func_array($ca, func_get_args());
                    call_user_func($ca, $params);
                } catch (Exception $ee) {
                    //do nothing
                }
            }
        }
    }

    /**
     * 
     * @param string $message
     * @param string $class
     * @param string $method
     * @return \GEM\Event
     */
    public static function F(string $message, string $class, string $method): Event {
        return new static($message, $class, $method);
    }

    public static function mk_event_key(string $message, string $class, string $method): string {
        return md5(mb_strtolower(implode("*", [
            get_called_class(), $message, trim($class, "\\/"), trim($method)]), 'UTF-8'));
    }

}
