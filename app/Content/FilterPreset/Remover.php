<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\FilterPreset;

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
     * @return \Content\FilterPreset\Remover
     */
    public static function F(int $id): Remover {
        return new static($id);
    }

    public function run() {
        if ($this->id) {
            $preset = null;
            try {
                $preset = FilterPreset::F()->load($this->id);
            } catch (\Throwable $e) {
                $preset = null;
            }
            if ($preset) {
                foreach ($preset->items as $item) {
                    $this->remove_item($item);
                }
                $this->remove_preset($preset);
            }
            \Cache\FileBeaconDependency::F(FilterPreset::CACHE_BEAKON)->reset_dependency_beacons();
        }
    }

    protected function remove_preset(FilterPreset $x) {
        \ImageFly\ImageFly::F()->remove_images(FilterPreset::MEDIA_CONTEXT, (string) $x->id);
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM filterpreset WHERE id=:P")->push_param(":P", $x->id)->execute();
    }

    protected function remove_item(FilterPresetItem $item) {
        if ($item->image) {
            \ImageFly\ImageFly::F()->remove_images(FilterPresetItem::MEDIA_CONTEXT, "{$item->id}_{$item->uid}");
        }
    }

}
