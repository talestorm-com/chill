<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * @property string $redirect_url
 */
class MailformedImageSpec extends \Errors\common_error {

    use \common_accessors\TCommonAccess;

    protected $redirect_url;

    public function __construct(string $message) {
        $this->redirect_url = $message;
        parent::__construct("mailformed image spec");
    }

    protected function __get__redirect_url() {
        return $this->redirect_url;
    }

}
