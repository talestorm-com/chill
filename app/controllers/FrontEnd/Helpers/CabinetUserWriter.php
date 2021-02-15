<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd\Helpers;

/**
 * Description of CabinetUserWriter
 *
 * @author eve
 */
class CabinetUserWriter {

    public static function run() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
            'email' => ['Strip', 'Trim', 'NEString', 'EmailMatch',],
            'phone' => ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString',],
            'family' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            // 'eldername' => ['Strip', 'Trim', 'NEString', "DefaultEmptyString"],
            // "news" => ["Boolean", "DefaultTrue"],
            "password" => ['Strip', 'Trim', 'NEString', "DefaultNull"],
            "repassword" => ['Strip', 'Trim', 'NEString', "DefaultNull"],
            'token'=>['Strip','Trim','NEString','DefaultEmptyString'],
                //"about" => ["Strip", "Trim", "NEString", "DefaultEmptyString"],
                // "sport" => ["Strip", "Trim", "NEString", "DefaultEmptyString"],
                // "hole_id" => ['IntMore0', 'DefaultNull'],
                // "time_map" => ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"],
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        \Helpers\Helpers::csrf_check_throw('profile',$data['token'],false);
        $current_id = \Auth\Auth::F()->get_id();
        $login_id = \Auth\UserInfo::S($data['email']);
        if ($login_id && $login_id->valid && $login_id->id !== $current_id) {
            \Errors\common_error::R("Этот логин уже занят другим пользователем");
        }
        $phone_id = \Auth\UserInfo::PHONE($data['phone']);
        if ($phone_id && $phone_id->valid && $phone_id->id !== $current_id) {
            \Errors\common_error::R("Этот номер телефона уже зарегистрирован на другого пользователя");
        }
        $b = \DB\SQLTools\SQLBuilder::F();
        /*  */
        $b->push("
            UPDATE user SET 
          login=:P{$b->c}login
          WHERE id=:P{$b->c}id;
                UPDATE user__fields SET                 
                 name=:P{$b->c}name,
                 phone=:P{$b->c}phone,
                 family=:P{$b->c}family                 
                WHERE id=:P{$b->c}id;
                ");
        $b->push_params([
            ":P{$b->c}login" => $data['email'],
            ":P{$b->c}phone" => $data["phone"],
            ":P{$b->c}name" => $data["name"],
            ":P{$b->c}family" => $data["family"],
            // ":P{$b->c}eldername" => $data['eldername'],
            // ":P{$b->c}news" => $data["news"] ? 1 : 0,
            ":P{$b->c}id" => $current_id,
        ]);
        $b->inc_counter();
        if ($data['password']) {
            $enc_pass = \Auth\UserInfo::encrypt_password($data['password']);
            $b->push("UPDATE user set pass=:P{$b->c}pass WHERE id=:P{$b->c}id;");
            $b->push_params([
                ":P{$b->c}pass" => $enc_pass,
                ":P{$b->c}id" => $current_id
            ]);
        }
        $b->execute_transact();
        \Auth\Auth::F()->force_login($current_id);
        if (count(\DataMap\FileMap::F()->get_by_field_name('ava'))) {
            \ImageFly\ImageFly::F()->process_upload_manual("avatar", $current_id, md5("avatar"), \DataMap\FileMap::F()->get_by_field_name("ava")[0]);
        }
        \Helpers\Helpers::csrf_remove($data['token'],'profile');
    }

}
