<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CDN_DRIVER;

/**
 * Description of CDNUrlMaker
 *
 * @author eve
 */
class CDNUrlMaker {

    //put your code here

    public static function mk_url($method, $base) {
        $data = [
            "apiuserid" => \Config\Config::F()->CDN_API_ID,
            "timestamp" => time(),
        ];
        $encoded_data = [];
        foreach ($data as $key => $value) {
            $encoded_data[] = sprintf("%s=%s", urlencode($key), urlencode($value));
        }
        $query_hash = "{$method}+api.platformcraft.ru/1/{$base}?" . implode("&", $encoded_data);
        $hash = hash_hmac('sha256', $query_hash, \Config\Config::F()->CDN_KEY);
        $encoded_data[] = "hash={$hash}";
        return "https://api.platformcraft.ru/1/{$base}?" . implode("&", $encoded_data);
    }

}
