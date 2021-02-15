<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Removers;

/**
 * Description of VideoRemover
 *
 * @author eve
 */
class TEXTRemover {
 
    //put your code here
    private $id;

    protected function __construct(int $id) {
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
        try {
            $content = \Content\MediaContent\Readers\ctTEXT\MediaContentObject::F($this->id);
            \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\ctTEXT\MediaContentObject::MEDIA_CONTEXT, $content->id);
            \DB\DB::F()->exec("DELETE FROM media__content WHERE id=:P", [":P" => $content->id]);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

}
