<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd\Helpers;

/**
 * Description of SoapAccessReader
 *
 * @author eve
 */
class SoapAccessReader {
    //put your code here

    /**
     * 
     * @param int $content_id
     * @return array
     */
    public static function run(int $content_id) {
        $content_info = \DB\DB::F()->queryRow("SELECT A.id,A.enabled,
            COALESCE(P.price,0)price,CASE WHEN CA.deadline IS NULL THEN 0 ELSE CA.deadline END - UNIX_TIMESTAMP(NOW()) time_left,
            CA.links
            FROM media__content A 
            LEFT JOIN media__content__price P ON(P.id=A.id)
            LEFT JOIN media__content__user__access CA ON(CA.media_id=A.id AND CA.user_id=:Pu)
            WHERE A.id=:P 
            ", [":P" => $content_id, ":Pu" => \Auth\Auth::F()->is_authentificated() ? \Auth\Auth::F()->get_id() : null]);
        return $content_info ? $content_info : null;
    }

}
