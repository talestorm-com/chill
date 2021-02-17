<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd\Helpers;

/**
 * Description of ChillCommentWriter
 *
 * @author eve
 */
class ChillCommentWriter {

    public static function run() {
        if (\Auth\Auth::F()->is_authentificated()) {
            $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
                'sticker' => ['IntMore0', 'DefaultNull'],
                'text' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'token' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            ]);
            if (!$data['text'] && !$data['sticker']) {
                \Errors\common_error::R("Выберите стикер или написшите что-нибудь");
            }
            \Helpers\Helpers::csrf_check_throw('comchill', $data['token'], false);
            $query = "INSERT INTO chill__review(datum,enabled,author,sticker,content,r)
            VALUES(NOW(),0,:Pa,:Ps,:Pc,'');
            ";
            \DB\DB::F()->exec($query, [":Pa" => \Auth\Auth::F()->get_user_info()->name,
                ":Ps" => $data['sticker'], ":Pc" => \Helpers\Helpers::NEString($data['text'], '')]);
            \Helpers\Helpers::csrf_remove($data['token'], 'comchill');
        }
    }

}
