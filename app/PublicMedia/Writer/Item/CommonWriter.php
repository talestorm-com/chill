<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Item;

/**
 * Description of CommonWriter
 *
 * @author eve
 */
class CommonWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \PublicMedia\Writer\Item\CommonWriter
     */
    public static function F(): CommonWriter {
        return new static();
    }

    public function run(Writer $w) {
        $uid = $w->data_input->get_filtered("id", ['IntMore0', 'DefaultNull']);
        if ($uid) {
            $this->do_update($w);
        } else {
            $this->do_create($w);
        }
    }

    public function do_create(Writer $w) {
        $w->runtime->set("mode", "create");
        if (!$w->upload_info->measurement) {
            \Errors\common_error::R("no file in known format found");
        }
        if(!$w->medial_object){
            \Errors\common_error::R("no parent gallery");
        }
        $data = \Filters\FilterManager::F()->apply_filter_datamap($w->data_input, $this->get_create_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $b = $w->builder; /* @var $b \DB\SQLTools\SQLBuilder */        
        $b->push("INSERT INTO public__gallery__item(gallery_id,title,active,type,extension,aspect,preview_aspect,sort,info) 
            VALUES(:P{$b->c}gallery_id,            
            :P{$b->c}title,:P{$b->c}active,:P{$b->c}type,:P{$b->c}extension,
            :P{$b->c}aspect,:P{$b->c}preview_aspect,:P{$b->c}sort,:P{$b->c}info
            );
            SET {$w->temp_var} =  LAST_INSERT_ID();
            ");

        $b->push_params([
            ":P{$b->c}gallery_id" => $w->medial_object->id,
            ":P{$b->c}title" => $data["title"],
            ":P{$b->c}active" => $data['active'] ? 1 : 0,
            ":P{$b->c}type" => $w->upload_info->measurement->type === \ImageFly\ImageMeasureResult::IMAGE_TYPE_VIDEO ? $w->upload_info->file_type : "image/jpeg",
            ":P{$b->c}extension" => $w->upload_info->measurement->type === \ImageFly\ImageMeasureResult::IMAGE_TYPE_VIDEO ? $w->upload_info->file_extension : "jpg",
            ":P{$b->c}aspect" => $w->upload_info->measurement->aspect,
            ":P{$b->c}preview_aspect" => $w->upload_info->has_preview ? $w->upload_info->preview_measurement->aspect : $w->upload_info->measurement->aspect,
            ":P{$b->c}sort" => $data["sort"],
            ":P{$b->c}info" => $data["info"],
        ]);
        $b->inc_counter();
    }

    protected function get_create_filters() {
        return [            
            'title' => ["Trim", "NEString", "DefaultEmptyString"],
            'active' => ["Boolean", "DefaultTrue"],
            'sort' => ["AnyInt", "Default0"],
            'info' => ["Trim", "NEString", "DefaultEmptyString"],
        ];
    }

    public function do_update(Writer $w) {
        $w->runtime->set("mode", "update");
        $data = \Filters\FilterManager::F()->apply_filter_datamap($w->data_input, $this->get_update_filters());
        $cdata = [];
        foreach ($data as $key => $value) {
            if (!\Filters\EmptyValue::is($value)) {
                $cdata[$key] = $value;
            }
        }
        if ($w->upload_info->has_file) {
            $cdata["type"] = $w->upload_info->is_video ? $w->upload_info->file_type : "image/jpeg";
            $cdata["extension"] = $w->upload_info->is_video ? $w->upload_info->file_extension : "jpg";
            $cdata["aspect"] = $w->upload_info->measurement->aspect;
            $cdata["preview_aspect"] = $w->upload_info->has_preview ? $w->upload_info->preview_measurement->aspect : $w->upload_info->measurement->aspect;
        }
        if ($w->upload_info->has_preview) {
            $cdata["preview_aspect"] = $w->upload_info->preview_measurement->aspect;
        }
        if (count($cdata) > 1 && array_key_exists("id", $cdata)) {
            $source = null;
            try {
                $source = \PublicMedia\PublicMediaItemShort::F()->load( $cdata["id"]);
            } catch (\Throwable $e) {
                $source = null;
            }

            if (!($source && $source->valid && $w->user_id === $source->owner_id)) {
                \Errors\common_error::R("not found");
            }
            if(!$w->medial_object){
                $w->init_medial_object($source->gallery_id);
            }
            if(!$w->medial_object){
                \Errors\common_error::R("no parent gallery found");
            }
            \Filters\FilterManager::F()->raise_array_error($cdata);
            $b = $w->builder; /* @var $b \DB\SQLTools\SQLBuilder */
            $b->push("SET {$w->temp_var} = :P{$b->c}id;");
            $b->push_param(":P{$b->c}id", $source->id);
            $ups = [];
            $pp = [];
            foreach ($cdata as $key => $value) {
                if ($key !== "id") {
                    $ups[] = sprintf("%s=:P%d%s", $key, $b->c, $key);
                    $pp[sprintf(":P%d%s", $b->c, $key)] = is_bool($value) ? ($value ? 1 : 0) : $value;
                }
            }

            if (count($ups)) {
                $b->push(sprintf("UPDATE public__gallery__item SET %s WHERE id={$w->temp_var};", implode(",", $ups)));
                $b->push_params($pp);                
                $b->inc_counter();
                
            }
        }
    }

    protected function get_update_filters() {
        return [
            'title' => ["Trim", "NEString",],
            'active' => ["Boolean",],
            'sort' => ["AnyInt",],
            'info' => ["Trim", "NEString",],
            'id' => ["IntMore0", "Default0"],
        ];
    }

}
