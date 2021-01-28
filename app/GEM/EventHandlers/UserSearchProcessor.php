<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GEM\EventHandlers;

/**
 * Триггерный класс обработчика сохранения пользователя.
 * заполнит поисковые поля фио и телефона
 */
class UserSearchProcessor extends AbstractEventHandler {

    public function run(\GEM\EventKVS $params = null) {
        if ($params) {
            $user_id = \Filters\FilterManager::F()->apply_chain($params->get('user_id'), ['IntMore0', 'DefaultNull']);            
            if ($user_id) {
                $query = "SELECT A.id,phone,name,family,eldername FROM user A LEFT JOIN user__fields B ON(A.id=B.id) WHERE A.id=:P";
                $row = \DB\DB::F()->queryRow($query, [":P" => $user_id]);
                if ($row) {
                    $f = ["Strip", 'Trim', 'NEString', 'DefaultEmptyString'];
                    $full_name = trim(implode(" ", [
                        \Filters\FilterManager::F()->apply_chain($row["family"], $f),
                        \Filters\FilterManager::F()->apply_chain($row['name'], $f),
                        \Filters\FilterManager::F()->apply_chain($row['eldername'], $f)
                    ]));
                    $phone = \Filters\FilterManager::F()->apply_chain($row['phone'], ["Strip", "Trim", "NEString", "Digits", "PhoneMatch", "Digits", "DefaultEmptyString"]);
                    \DB\DB::F()->exec("INSERT INTO user__search(id,search_name,search_phone) VALUES(:Pi,:Pn,:Pp) ON DUPLICATE KEY UPDATE 
                    search_name=VALUES(search_name), search_phone=VALUES(search_phone);", [":Pi" => $user_id, ":Pn" => $full_name, ":Pp" => $phone]);
                    
                }
            }
        }
    }

    protected static function get_message() {
        return "ON_USER_DATA_CHANGED";
    }

}

UserSearchProcessor::register();
