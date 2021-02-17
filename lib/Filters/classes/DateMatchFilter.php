<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class DateMatchFilter extends \Filters\AbstractFilter {

    CONST P1 = '/(?P<Y>\d{4})-(?<M>\d{1,2})-(?P<D>\d{1,2})(?:\s{1,}(?P<H>\d{1,2})(?::(?P<I>\d{1,2})(?::(?<S>\d{1,2})){0,1}){0,1}){0,1}/';
    CONST P2 = '/(?P<D>\d{1,2})[\.|\,|\-|\\|\/](?<M>\d{1,2})[\.|\,|\-\|\\|\/](?P<Y>\d{4})(?:\s{1,}(?P<H>\d{1,2})(?::(?P<I>\d{1,2})(?::(?<S>\d{1,2})){0,1}){0,1}){0,1}/';

    /**
     * 
     * @param array $m
     * @return \DateTime
     */
    protected function mkArrayDate(array $m) {
        $d = new \DateTime();
        $d->setDate(intval($m['Y']), intval($m['M']), intval($m['D']));
        $d->setTime(array_key_exists('H', $m) ? intval($m['H']) : 0, array_key_exists('I', $m) ? intval($m['I']) : 0, array_key_exists('S', $m) ? intval($m['S']) : 0);
        return $d;
    }

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        $m = '';
        if (is_object($input_value) && ($input_value instanceof \DateTime)) {
            return $input_value;
        } elseif (is_string($input_value) && preg_match(static::P1, $input_value, $m)) {
            return $this->mkArrayDate($m);
        } elseif (is_string($input_value) && preg_match(static::P2, $input_value, $m)) {
            return $this->mkArrayDate($m);
        }
        return \Filters\InvalidValue::F("not valid datetime");
    }

}
