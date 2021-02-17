<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\Common;

class ImportRedirectException extends \Exception {

    /** @var array */
    protected $url_params;

    public function get_url_params() {
        return $this->url_params;
    }

    public static function F(array $redirect_params) {
        $e = new static("redirect");
        $e->url_params = $redirect_params;
        throw $e;
    }

}
