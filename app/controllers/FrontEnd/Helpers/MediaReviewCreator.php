<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd\Helpers;

/**
 * Description of MediaReviewCreator
 *
 * @author eve
 */
class MediaReviewCreator {

    public static function run() {
        if (\Auth\Auth::F()->is_authentificated()) {
            $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
                'content_id' => ['IntMore0'],
                'rate' => ['IntMore0'],
                'comment' => ['Strip', 'Trim', 'NEString'],
                'csrf' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            ]);
            \Filters\FilterManager::F()->raise_array_error($data);
            \Helpers\Helpers::csrf_check_throw("review", $data['csrf'],false);
            $data['rate'] = min([5, max([1, $data['rate']])]);
            \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO media__content__review(media_id,user_id,	rate,post,approved,info)
            VALUES (:Pmid,:Puid,:Pr,NOW(),0,:Pi)
            ON DUPLICATE KEY UPDATE 	rate=VALUES(	rate),post=VALUES(post),approved=VALUES(approved),info=VALUES(info);
            ")->push_params([
                ":Pmid" => $data['content_id'],
                ":Puid" => \Auth\Auth::F()->get_id(),
                ":Pr" => $data['rate'],
                ":Pi" => $data['comment'],
            ])->execute_transact();
            \Helpers\Helpers::csrf_remove( $data['csrf'],"review");
        }
    }

}
