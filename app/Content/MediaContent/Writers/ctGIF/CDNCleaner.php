<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctGIF;

/**
 * Description of CDNCleaner
 *
 * @author eve
 */
class CDNCleaner {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run(Writer $writer) {
        $raw_data = \Filters\FilterManager::F()->apply_filter_datamap($writer->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($raw_data);
        if ($raw_data['id']) {
            $map = \DataMap\FileMap::F();
            $file = $map->get_by_field_name("gif_file");
            if (count($file)) {
                $cdn_id = \Filters\FilterManager::F()->apply_chain(\DB\DB::F()->queryScalar("SELECT cdn_id FROM media__content__gif WHERE id=:P", [":P" => $raw_data['id']]), ['Strip', 'Trim', 'NEString', 'DefaultNull']);
                if ($cdn_id) {
                    \Content\MediaContent\Removers\CDNRemoveTask::mk_params()->add("files", [$cdn_id])->run();                    
                }
            }
        }
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
        ];
    }

}
