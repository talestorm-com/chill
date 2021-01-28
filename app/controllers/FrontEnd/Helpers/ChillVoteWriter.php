<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd\Helpers;

/**
 * Description of ChillVoteWriter
 *
 * @author eve
 */
class ChillVoteWriter {

    public static function run() {
        $comment_id = \DataMap\InputDataMap::F()->get_filtered('comment_id', ['IntMore0', 'DefaultNull']);
        $value = \DataMap\InputDataMap::F()->get_filtered('value', ['Int', 'Default0']);
        $token = \DataMap\InputDataMap::F()->get_filtered('token', ['Strip','Trim','NEString', 'DefaultEmptyString']);        
        if (\Auth\Auth::F()->is_authentificated()) {
            \Helpers\Helpers::csrf_check_throw('commchillvote',$token,false);
            if ($comment_id && $value !== 0) {
                $query = "SELECT * FROM chill__review__rating__usages WHERE id=:P AND user_id=:PP";
                $row = \DB\DB::F()->queryRow($query, [":P" => $comment_id, ":PP" => \Auth\Auth::F()->get_id()]);
                if ($row) {
                    \Errors\common_error::R("Вы уже оценили этот отзыв!");
                }
                $b = \DB\SQLTools\SQLBuilder::F();
                $b->push("INSERT INTO chill__review__rating__usages(id,user_id) VALUES(:Pi,:Pu);
                INSERT INTO chill__review__rating(id,rating) VALUES(:Pi,:Px) ON DUPLICATE KEY UPDATE rating=VALUES(rating);
                ")->push_params([
                    ":Pi" => $comment_id,
                    ":Pu" => \Auth\Auth::F()->get_id(),
                    ':Px' => $value > 0 ? 1 : -1
                ])->execute_transact();
            }
        }
    }

}
