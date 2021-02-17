<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace VisitCounter;

/**
 * Description of VisitCounter
 *
 * @author eve
 * @property int $value
 */
class VisitCounter {

    use \common_accessors\TCommonAccess;

    protected static $instance;
    protected $value;
    protected $key = null;

    protected function __get__value() {
        return $this->value;
    }

    protected function __construct() {
        static::$instance = $this;
        $this->key = 'co_' . md5(__CLASS__);
        if (!\DataMap\CookieDataMap::F()->exists($this->key)) {
            setcookie($this->key, 1, 0, "/", "", true, true);
            \DB\DB::F()->exec("INSERT INTO visit_counter__operative(d) VALUES(NOW());");
        }
        $this->value = intval(\DB\DB::F()->queryScalari("SELECT q FROM visit_counter WHERE v='v'"));
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

}
