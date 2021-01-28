<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Removers;

/**
 * Description of SeasonSerieRemover
 *
 * @author eve
 */
class SeasonSerieRemover {

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
        $serie = \Content\MediaContent\Readers\ctSEASONSERIES\MediaContentObject::F($this->id);
        \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\ctSEASONSERIES\MediaContentObject::MEDIA_CONTEXT_POSTERS, $serie->id);
        \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_PREVIEW, $serie->id);
        $files_to_delete = [];
        foreach ($serie->files as $file /* @var $file \Content\MediaContent\FileList\FileListItem */) {
            $files_to_delete[] = $file->cdn_id;
        }
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM media__content WHERE id=:P;")->push_param(":P", $serie->id)->execute_transact();
        if (count($files_to_delete)) {
            CDNRemoveTask::mk_params()->add("files", $files_to_delete)->run();
        }
    }

}
