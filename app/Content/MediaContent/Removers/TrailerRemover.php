<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Removers;

/**
 * Description of TrailerRemover
 *
 * @author eve
 */
class TrailerRemover {

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
            $trailer = \Content\MediaContent\Readers\Trailer\MediaContentObject::F($this->id);
            \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\Trailer\MediaContentObject::MEDIA_CONTEXT, $trailer->id);
            $files = [];
            foreach ($trailer->files as $file /* @var $file \Content\MediaContent\FileList\FileListItem */) {
                $files[] = $file->cdn_id;
            }
            if (count($files)) {
                CDNRemoveTask::mk_params()->add("files", $files)->run();
            }
            \DB\DB::F()->exec("DELETE FROM media__content WHERE id=:P", [":P" => $trailer->id]);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

}
