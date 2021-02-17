<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class HTMLColorFilter extends \Filters\AbstractFilter {

    protected static $colors = [
        'white' => '#ffffff',
        'black' => '#000000',
        'netline' => '#00acc8',
    ];
    
    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        $value = $input_value;
        if (is_string($value)) {
            $m = [];
            if (preg_match("/^(?P<col>#[0-9a-f]{6})$/i", trim($value), $m)) {
                return mb_strtolower($m['col'], 'UTF-8');
            }
            if (preg_match("/^(?P<col>rgba\s{0,}\(\s{0,}\d{1,3}\s{0,},\s{0,}\d{1,3}\s{0,},\s{0,}\d{1,3}\s{0,},\s{0,}[0-9\.]{1,}\s{0,}\))$/i", trim($value), $m)) {
                return mb_strtolower(str_ireplace(" ", '', $m['col']), 'UTF-8');
            }
            if (array_key_exists(mb_strtolower(trim($value), 'UTF-8'), static::$colors)) {
                return static::$colors[mb_strtolower(trim($value), 'UTF-8')];
            }
        }
        return \Filters\InvalidValue::F("not a color");
    }

}
