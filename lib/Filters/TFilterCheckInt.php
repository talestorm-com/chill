<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

trait TFilterCheckInt {

    protected function check_int_ok(int $length, IFilterParams $params = null): int {
        if ($params) {
            if ($params->exists('min')) {
                $param = $params->get('min');
                if (is_numeric($param) && $length < intval($param)) {
                    return -1;
                }
            }
            if ($params->exists('max')) {
                $param = $params->get('max');
                if (is_numeric($param) && $length > intval($param)) {
                    return 1;
                }
            }
            if ($params->exists('more')) {
                $param = $params->get('more');
                if (is_numeric($param) && $length <= intval($param)) {
                    return -1;
                }
            }
            if ($params->exists('less')) {
                $param = $params->get('less');
                if (is_numeric($param) && $length >= intval($param)) {
                    return 1;
                }
            }
        }
        return 0;
    }

}
