<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Gallery;

/**
 * Description of TextWriter
 *  пост врайтер!!!
 * @author eve
 */
class TagWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * 
     * @return \PublicMedia\Writer\Gallery\TagWriter
     */
    public static function F(): TagWriter {
        return new static();
    }

    public function run(Writer $w) {
        if ($w->data_input->exists("tags")) {
            $tags = $w->data_input->get_filtered("tags", ["NEArray", "ArrayOfNEString", "NEArray", "DefaultEmptyArray"], \Filters\params\ArrayParamBuilder::B(["ArrayOfNEString" => ["more" => 1]], true)->get_param_set_for_property());
            $b = \DB\SQLTools\SQLBuilder::F();
            $b->push("DELETE FROM public__gallery__tag WHERE gallery_id=:P{$b->c}gallery_id;");
            $b->push_param(":P{$b->c}gallery_id", $w->result_id);
            $b->inc_counter();
            $tags_to_set = \PublicMedia\PublicTag::decode_tags_string($tags);
            $ins = [];
            $pp = [];
            $c = 0;
            foreach ($tags_to_set as $tag /* @var $tag \PublicMedia\PublicTag */) {
                $ins[] = "(:P{$b->c}gallery,:P{$b->c}_{$c}_tag)";
                $pp[":P{$b->c}_{$c}_tag"] = $tag->id;
                $c++;
            }
            if (count($ins)) {
                $b->push(sprintf("INSERT INTO public__gallery__tag (gallery_id,tag_id) VALUES %s ON DUPLICATE KEY UPDATE tag_id=VALUES(tag_id)", implode(",", $ins)));
                $b->push_params($pp);
                $b->push_param(":P{$b->c}gallery", $w->result_id);
            }
            $b->execute();
        }
    }

}
