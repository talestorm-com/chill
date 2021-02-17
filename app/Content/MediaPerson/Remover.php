<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaPerson;

/**
 * Description of Remover
 *
 * @author eve
 */
class Remover {
    //put your code here

    /** @var int */
    private $id;

    public function __construct(int $id) {
        $this->id = $id;
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id) {
        return new static($id);
    }

    public function run() {
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("DELETE FROM media__content__actor WHERE id=:P");
        $b->push_param(":P", $this->id);
        $b->execute_transact();
        \ImageFly\ImageFly::F()->remove_images(MediaPerson::MEDIA_CONTEXT, $this->id);
        MediaPerson::reset_cached();
    }

}
