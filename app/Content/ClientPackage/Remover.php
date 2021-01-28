<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ClientPackage;

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
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM fitness__package WHERE id=:P")->push_param(":P", $id)->execute_transact();
        \ImageFly\ImageFly::F()->remove_images(Package::MEDIA_CONTEXT, (string)$id);
        
    }

    /**
     * 
     * @return \Content\ClientPackage\Remover
     */
    public static function F(): Remover {
        return new static();
    }

}
