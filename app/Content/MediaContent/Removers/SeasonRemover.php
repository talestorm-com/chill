<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Removers;

/**
 * Description of SeasonRemover
 *
 * @author eve
 */
class SeasonRemover {

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
        $builder = \DB\SQLTools\SQLBuilder::F();
        $files_to_remove = [];
        $this->run_pending($builder, $files_to_remove);
        if (!$builder->empty) {
            $builder->execute_transact();
        }
        if (count($files_to_remove)) {
            CDNRemoveTask::mk_params()->add("files", $files_to_remove)->run();
        }
    }

    public function run_pending(\DB\SQLTools\SQLBuilder $builder, array &$files) {
        $soap = \Content\MediaContent\Readers\ctSEASON\MediaContentObject::F($this->id);
        $seasons_ids_raw = \DB\DB::F()->queryAll("SELECT id FROM media__content__season__season WHERE season_id=:P", [":P" => $soap->id]);
        $seasons_ids = [];
        foreach ($seasons_ids_raw as $row) {
            $season_id = \Filters\FilterManager::F()->apply_chain($row["id"], ["IntMore0", "DefaultNull"]);
            $season_id ? $seasons_ids[] = $season_id : 0;
        }
        if (count($seasons_ids)) {
            foreach ($seasons_ids as $season_id) {
                SeasonSeasonRemover::F($season_id)->run_pending($builder, $files);
            }
        }
        if ($soap->video_cdn_id) {
            $files[] = $soap->video_cdn_id;
        }
        if ($soap->gif_cdn_id) {
            $files[] = $soap->gif_cdn_id;
        }
        TrailerListCleaner::F($soap->id)->run_pending($builder, $files);
        \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_POSTERS, $soap->id);
        \ImageFly\ImageFly::F()->remove_images(\Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_FRAMES, $soap->id);
        \ImageFly\ImageFly::F()->remove_images('lent_poster', $soap->id);
        $builder->inc_counter()->push("DELETE FROM media__content WHERE id=:P{$builder->c}id;")->push_param(":P{$builder->c}id", $soap->id)->inc_counter();
    }

}
