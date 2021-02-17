<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Review;

/**
 * Description of Writer
 *
 * @author eve
 * @property int $user_id
 * @property int $media_id
 */
class Writer extends \Content\MediaContent\Writers\AWriter {

    /** @var int */
    protected $user_id;

    /** @var int */
    protected $media_id;

    /** @return int */
    protected function __get__user_id() {
        return $this->user_id;
    }

    /** @return int */
    protected function __get__media_id() {
        return $this->media_id;
    }

    /**
     * 
     * @return $this
     */
    public function run() {
        $this->builder->inc_counter();
        $data = \Filters\FilterManager::F()->apply_filter_datamap($this->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $data['rate'] = min([max([1, $data['rate']]), 5]);
        $this->media_id=$data['media_id'];
        $this->user_id = $data['user_id'];
        $this->builder->push("UPDATE media__content__review SET approved=:Papproved,info=:Pinfo,rate=:Prate WHERE user_id=:Puser AND media_id=:Pmedia;")
                ->push_params([
                    ":Puser" => $data['user_id'],
                    ":Pmedia" => $data['media_id'],
                    ':Papproved' => $data['approved'] ? 1 : 0,
                    ':Pinfo' => $data['info'],
                    ':Prate' => $data['rate'],
                ])->execute_transact();
    }

    protected function get_filters() {
        return [
            'media_id' => ['IntMore0'],
            'user_id' => ['IntMore0'],
            'rate' => ['IntMore0'],
            'approved' => ['Boolean', 'DefaultFalse'],
            'info' => ['Strip', 'Trim', 'NEString'],
        ];
    }

}
