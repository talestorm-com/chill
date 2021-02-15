<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\TrainingHall;

/**
 * Description of Remover
 *
 * @author eve
 */
class Remover {

    public function __construct() {
        ;
    }

    public function run(int $id) {
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM fitness__places WHERE id=:P")->push_param(":P", $id)->execute_transact();
        \ImageFly\ImageFly::F()->remove_images(TrainingHall::MEDIA_CONTEXT, (string)$id);
        
    }

    /**
     * 
     * @return \Content\TrainingHall\Remover
     */
    public static function F(): Remover {
        return new static();
    }

}
