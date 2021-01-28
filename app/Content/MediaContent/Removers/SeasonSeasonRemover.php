<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Removers;

/**
 * Description of SeasonSeasonRemover
 *
 * @author eve
 */
class SeasonSeasonRemover {

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
        //удалить все серии (поименно с перечислением CDN)
        // удалить все кртинки
        //удалить все трейлеры
        $builder = \DB\SQLTools\SQLBuilder::F();
        $files_to_delete = [];
        $this->run_pending($builder, $files_to_delete);
        if (!$builder->empty) {
            $builder->execute_transact();
        }
        if (count($files_to_delete)) {
            CDNRemoveTask::mk_params()->add("files", $files_to_delete)->run();
        }
    }

    public function run_pending(\DB\SQLTools\SQLBuilder $builder, array &$files_to_delete) {

        $seson = \Content\MediaContent\Readers\ctSEASONSEASON\MediaContentObject::F($this->id);
        $series_ids_raw = \DB\DB::F()->queryAll("SELECT id FROM media__content__season__series WHERE seasonseason_id=:P", [":P" => $seson->id]);
        $series_ids = [];
        foreach ($series_ids_raw as $series_row) {
            $series_id = \Filters\FilterManager::F()->apply_chain($series_row['id'], ['IntMore0', 'DefaultNull']);
            $series_id ? $series_ids[] = $series_id : 0;
        }
        $series_ids = array_unique($series_ids);

        if (count($series_ids)) {
            foreach ($series_ids as $series_id) {
                $this->remove_series($series_id, $builder, $files_to_delete);
            }
        }
        TrailerListCleaner::F($seson->id)->run_pending($builder, $files_to_delete);
        $builder->inc_counter()->push("DELETE FROM media__content WHERE id=:P{$builder->c}id;")->push_param(":P{$builder->c}id", $seson->id)->inc_counter();
        \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_POSTERS, $seson->id);
    }

    protected function remove_series(int $series_id, \DB\SQLTools\SQLBuilder $builder, array &$files_to_delete) {
        $serie = null;
        try {
            $serie = \Content\MediaContent\Readers\ctSEASONSERIES\MediaContentObject::F($series_id);
        } catch (\Throwable $e) {
            return;
        }
        $builder->inc_counter();
        \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\ctSEASONSERIES\MediaContentObject::MEDIA_CONTEXT_POSTERS, $serie->id);
        \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_PREVIEW, $serie->id);
        $builder->push("DELETE FROM media__content WHERE id=:P{$builder->c}id;")->push_param(":P{$builder->c}id", $serie->id)->inc_counter();
        foreach ($serie->files as $file /* @var $file \Content\MediaContent\FileList\FileListItem */) {
            $files_to_delete[] = $file->cdn_id;
        }
    }

}
