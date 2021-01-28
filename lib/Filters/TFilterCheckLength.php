<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

trait TFilterCheckLength {

    protected function check_length_ok(int $length, IFilterParams $params = null): int {
        if ($params) {
            if ($params->exists('count_min')) {
                $param = $params->get('count_min');
                if (is_numeric($param) && $length < intval($param)) {
                    return -1;
                }
            }
            if ($params->exists('count_max')) {
                $param = $params->get('count_max');
                if (is_numeric($param) && $length > intval($param)) {
                    return 1;
                }
            }
            if ($params->exists('count_more')) {
                $param = $params->get('count_more');
                if (is_numeric($param) && $length <= intval($param)) {
                    return -1;
                }
            }
            if ($params->exists('count_less')) {
                $param = $params->get('count_less');
                if (is_numeric($param) && $length >= intval($param)) {
                    return 1;
                }
            }
        }
        return 0;
    }

}
