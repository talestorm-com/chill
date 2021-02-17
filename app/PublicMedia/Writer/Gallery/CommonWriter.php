<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Gallery;

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
     * @return \PublicMedia\Writer\Gallery\CommonWriter
     */
    public static function F(): CommonWriter {
        return new static();
    }

    public function run(Writer $w) {
        $id = $w->data_input->get_filtered("id", ['IntMore0', 'DefaultNull']);
        if ($id) {
            $this->do_update($w);
        } else {
            $this->do_create($w);
        }
    }

    public function do_create(Writer $w) {
        $w->runtime->set("mode", "create");
        $data = \Filters\FilterManager::F()->apply_filter_datamap($w->data_input, $this->get_create_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $b = $w->builder; /* @var $b \DB\SQLTools\SQLBuilder */
        $b->push("INSERT INTO public__gallery(owner_id,name,visible,cover_aspect) VALUES(
            :P{$b->c}owner_id,:P{$b->c}name,:P{$b->c}visible,:P{$b->c}cover_aspect);");
        $b->push("SET {$w->temp_var} = LAST_INSERT_ID();");
        $b->push_params([
            ":P{$b->c}owner_id" => $w->get_creator_id(),
            ":P{$b->c}name" => $data["name"],
            ":P{$b->c}visible" => $data['visible'] ? 1 : 0,
            ":P{$b->c}cover_aspect" => $data["cover_aspect"]
        ]);
        $b->inc_counter();
    }

    protected function get_create_filters() {
        return [
            'name' => ['Strip', 'Trim', 'NEString'],
            'visible' => ['Boolean', 'DefaultTrue'],
            'cover_aspect' => ['Float', 'Default1'],
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
        if (array_key_exists("id", $cdata) && is_int($cdata["id"])) {
            $source = null;
            try {
                $source = \PublicMedia\PublicMediaGalleryShort::F()->load_by_id($cdata["id"]);
            } catch (\Throwable $e) {
                $source = null;
            }

            if (!($source && $source->valid && $w->check_write_access($source->owner_id))) {
                \Errors\common_error::R("not found");
            }
        } else {
            \Errors\common_error::R("no id");
        }
        $b = $w->builder; /* @var $b \DB\SQLTools\SQLBuilder */
        $b->push("SET {$w->temp_var} = :P{$b->c}id;");
        $b->push_param(":P{$b->c}id", $source->id);
        if (count($cdata) > 1 && array_key_exists("id", $cdata)) {
            \Filters\FilterManager::F()->raise_array_error($cdata);
            $ups = [];
            $pp = [];
            foreach ($cdata as $key => $value) {
                if ($key !== "id") {
                    $ups[] = sprintf("%s=:P%d%s", $key, $b->c, $key);
                    $pp[sprintf(":P%d%s", $b->c, $key)] = is_bool($value) ? ($value ? 1 : 0) : $value;
                }
            }

            if (count($ups)) {
                $b->push(sprintf("UPDATE public__gallery SET %s WHERE id={$w->temp_var};", implode(",", $ups)));
                $b->push_params($pp);
                $b->inc_counter();
            }
        }
    }

    protected function get_update_filters() {
        return [
            "id" => ["IntMore0", "DefaultNull"],
            "name" => ["Strip", "Trim", "NEString"],
            "visible" => ["Boolean"],
                //"cover_aspect" => ["Float"],
        ];
    }

}
