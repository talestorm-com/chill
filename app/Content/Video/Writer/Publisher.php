<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video\Writer;

/**
 * Description of Publisher
 *
 * @author eve
 */
class Publisher {

    //put your code here

    public function __construct() {
        ;
    }

    public static function F(): Publisher {
        return new static();
    }

    public function run(VideoGroupWriter $w) {
        if ($w->data_input->get_filtered("active", ['Boolean', "DefaultFalse"])) {
            if ($w->common_input->get_filtered("create_message", ["Boolean", "DefaultFalse"])) {
                $keep = $w->common_input->get_filtered("create_message_keep", ["Boolean", "DefaultFalse"]);
                $draft_mode = $w->common_input->get_filtered("create_message_draft", ["Boolean", "DefaultFalse"]);
                \Content\Ribbon\RibbonItem::create_message_link("Новый видеокурс \"{$w->data_input->get_filtered("name", ["Strip", "Trim", "NEString"])}\"",
                         \Content\Video\VideoGroup::ACCESS_KEY, $w->result_id, null, $keep, $draft_mode);
                
            }
        }
    }

}
