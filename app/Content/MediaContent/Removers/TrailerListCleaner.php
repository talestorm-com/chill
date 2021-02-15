<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Removers;

/**
 * Description of TrailerListCleaner
 *
 * @author eve
 */
class TrailerListCleaner {

    //put your code here
    private $content_id;

    protected function __construct(int $content_id) {
        $this->content_id = $content_id;
    }

    /**
     * 
     * @param int $content_id
     * @return \static
     */
    public static function F(int $content_id) {
        return new static($content_id);
    }

    public function run_pending(\DB\SQLTools\SQLBuilder $builder, array &$file_list) {
        $ids = $this->get_trailes_ids();
        if (count($ids)) {
            foreach ($ids as $id) {
                try {
                    $trailer = \Content\MediaContent\Readers\Trailer\MediaContentObject::F($id);
                    foreach ($trailer->files as $file /* @var $file \Content\MediaContent\FileList\FileListItem */) {
                        $file_list[] = $file->cdn_id;
                    }
                    $this->remove_trailer($trailer, $builder);
                } catch (\Throwable $e) {
                    
                }
            }
        }
    }

    protected function get_trailes_ids() {
        $ids = [];
        $rows = \DB\DB::F()->queryAll('SELECT id FROM media__content__trailer WHERE content_id=:P', [':P' => $this->content_id]);
        foreach ($rows as $row) {
            $id = \Filters\FilterManager::F()->apply_chain($row['id'], ['IntMore0', 'DefaultNull']);
            if ($id) {
                $ids[] = $id;
            }
        }
        return array_unique($ids);
    }

    public function run() {
        $ids = $this->get_trailes_ids();
        if (count($ids)) {
            $files_to_remove = [];
            $builder = \DB\SQLTools\SQLBuilder::F();
            foreach ($ids as $id) {
                try {
                    $trailer = \Content\MediaContent\Readers\Trailer\MediaContentObject::F($id);
                    foreach ($trailer->files as $file /* @var $file \Content\MediaContent\FileList\FileListItem */) {
                        $files_to_remove[] = $file->cdn_id;
                    }
                    $this->remove_trailer($trailer, $builder);
                } catch (\Throwable $e) {
                    
                }
            }
            if (count($files_to_remove)) {
                CDNRemoveTask::mk_params()->add('files', $files_to_remove)->run();
            }
            if (!$builder->empty) {
                $builder->execute_transact();
            }
        }
    }

    protected function remove_trailer(\Content\MediaContent\Readers\Trailer\MediaContentObject $trailer, \DB\SQLTools\SQLBuilder $builder) {
        \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\Trailer\MediaContentObject::MEDIA_CONTEXT, $trailer->id);
        $builder->push("DELETE FROM media__content WHERE id=:P{$builder->c}id;")->push_param(":P{$builder->c}id", $trailer->id)->inc_counter();
    }

}
