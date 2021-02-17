<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Errors;

class common_error extends \Exception {

    protected $http_code = 0;

    public function get_http_code(): int {
        return $this->http_code;
    }

    public function __construct(string $message) {
        parent::__construct($message);
    }

    /**
     * 
     * @param string $message
     * @throws \static
     */
    public static function R(string $message) {
        throw new static($message);
    }

    /**
     * 
     * @param string $message
     * @param int $http_code
     * @throws static
     */
    public static function HR(string $message, int $http_code) {
        $e = new static($message);
        $e->http_code = $http_code;
        throw $e;
    }

    /**
     * 
     * @param string $format
     * @param any $args
     * @throws \static
     */
    public static function RF(string $format, ... $args) {
        static::R(call_user_func_array('sprintf', static::proper_args(func_get_args())));
    }
    /**
     * 
     * @param int $http_code
     * @param string $format
     * @param any $args
     * @throws static
     */
    public static function HRF(int $http_code,string $format, ... $args) {
        $args = array_slice(func_get_args(), 1);
        static::HR(call_user_func_array('sprintf', static::proper_args(func_get_args())), $http_code);        
    }
    
    
    protected static function proper_args(array $args){
        $aargs = [];
        for($i=0;$i<count($args);$i++){
            $aargs[]= htmlentities($args[$i], ENT_COMPAT, 'utf-8');
        }
        
        return $aargs;
    }
    

}
