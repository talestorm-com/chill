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
class VideoRemover {

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
            $content = \Content\MediaContent\Readers\ctVIDEO\MediaContentObject::F($this->id);
            TrailerListCleaner::F($content->id)->run();
            \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\ctVIDEO\MediaContentObject::MEDIA_CONTEXT_FRAMES, $content->id);
            \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\ctVIDEO\MediaContentObject::MEDIA_CONTEXT_POSTERS, $content->id);

            $files = [];
            foreach ($content->files as $file /* @var $file \Content\MediaContent\FileList\FileListItem */) {
                $files[] = $file->cdn_id;
            }

            \DB\DB::F()->exec("DELETE FROM media__content WHERE id=:P", [":P" => $content->id]);
            if (count($files)) {
                CDNRemoveTask::mk_params()->add("files", $files)->run();
            }
        } catch (\Throwable $e) {
            throw $e;
        }
    }

}
