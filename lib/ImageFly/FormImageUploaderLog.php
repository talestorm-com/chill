<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * Description of FormImageUploaderLog
 *
 * @author eve
 */
class FormImageUploaderLog {

    private $messages = null;

    public function __construct() {
        $this->messages = [];
    }

    public function message(string $s) {
        $this->messages[] = $s;
    }

    public function on_error(\Throwable $t) {
        $this->messages[] = "{$t->getMessage()} in {$t->getFile()} at {$t->getLine()}";
    }

    /**
     * 
     * @return string[]
     */
    public function get_messages() {
        return $this->messages;
    }

    public static function F(): FormImageUploaderLog {
        return new static();
    }

}
