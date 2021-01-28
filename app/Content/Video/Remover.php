<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video;

/**
 * Description of Remover
 *
 * @author eve
 * @property int $id
 */
class Remover {

    use \common_accessors\TCommonAccess;

    /** @var int */
    protected $id;

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    public function __construct(int $id) {
        $this->id = $id;
    }

    /**
     * 
     * @param int $id
     * @return \Content\Video\Remover
     */
    public static function F(int $id): Remover {
        return new static($id);
    }

    public function run() {
        if ($this->id) {
            $group = null;
            try {
                $group = VideoGroup::F()->load($this->id);
            } catch (\Throwable $e) {
                $group = null;
            }
            if ($group) {
                foreach ($group->items as $item) {
                    $this->remove_item($item);
                }
                $this->remove_group($group);
            }
            \Cache\FileBeaconDependency::F(VideoGroup::CACHE_BEAKON)->reset_dependency_beacons();
        }
    }

    protected function remove_group(VideoGroup $x) {
        \ImageFly\ImageFly::F()->remove_images(VideoGroup::MEDIA_CONTEXT, (string) $x->id);
        $vi_path = \Config\Config::F()->PROTECTED_VIDEOTUTORIALS_BASE . $x->id;
        \Helpers\Helpers::rm_dir_recursive($vi_path);
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM video__group WHERE id=:P")->push_param(":P", $x->id)->execute();
    }

    protected function remove_item(VideoItem $item) {
        if ($item->image) {
            \ImageFly\ImageFly::F()->remove_images(VideoItem::MEDIA_CONTEXT, "{$item->id}_{$item->uid}");
        }
        if ($item->video) {
            $rm_path = \Config\Config::F()->PROTECTED_VIDEOTUTORIALS_BASE . $item->id . DIRECTORY_SEPARATOR . $item->video;
            if (file_exists($rm_path) && is_file($rm_path) && is_writable($rm_path)) {
                @unlink($rm_path);
            }
        }
    }

}
