<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

class DefaultPropertiesFilterDelegate implements ImagePropertiesFilterDelegate {

    public function get_filters(string $mode = null): array {
        if ($mode) {
            $fn = "get_filters_for_mode_{$mode}";
            if (method_exists($this, $fn)) {
                return $this->$fn();
            }
        }
        return $this->get_default_filters();
    }

    protected function get_default_filters() {
        return [];
    }

    public function get_filters_params(string $mode = null) {
        if ($mode) {
            $fn = "get_filters_params_for_mode_{$mode}";
            if (method_exists($this, $fn)) {
                return $this->$fn();
            }
        }
        return $this->get_default_filters_params();
    }

    protected function get_default_filters_params() {
        return null;
    }

    public static function F(): ImagePropertiesFilterDelegate {
        return new static();
    }

}
