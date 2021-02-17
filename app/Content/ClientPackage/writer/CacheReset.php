<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\TrainingHall\writer;

/**
 * Description of CacheReset
 *
 * @author eve
 */
class CacheReset {

    //put your code here



    public function __construct() {
        ;
    }

    public static function F(): CacheReset {
        return new static();
    }

    public function run(writer $w) {
        \Content\TrainingHall\TrainingHall::reset_cache();
    }

}
