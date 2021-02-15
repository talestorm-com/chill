<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Item;

/**
 * Description of ReTagger
 *
 * @author eve
 */
class ReTagger {

    public function __construct() {
        ;
    }

    public static function F(): ReTagger {
        return new static();
    }

    public function run(Writer $w) {
        \PublicMedia\Writer\ReTagAsyncTask::mk_params()->add_array([
            'mode' => \PublicMedia\Writer\ReTagAsyncTask::MODE_ITEM_ONLY,
            'gallery_id' => $w->medial_object->id,
            'item_uid' => $w->writed_item->id,
        ])->execute();
    }

}
