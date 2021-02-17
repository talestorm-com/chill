<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RecaptchaV3;

/**
 * Description of recaptcha
 *
 * @author eve
 */
class recaptcha {

    //put your code here


    public static function run(string $captcha = null): bool {
        
        if ($captcha) {
            $secret = \PresetManager\PresetManager::F()->get_filtered('RECAPTCHA_SECRET_KEY', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            
            if ($secret) {
                try {
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_POST => true,
                        CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_POSTFIELDS => [
                            'secret' => $secret,
                            'response' => $captcha,
                        ]
                    ]);
                    $result = curl_exec($curl);                    
                    if ($result) {
                        $result_array = json_decode($result,true);                        
                        if (is_array($result_array)) {
                            $map = \DataMap\CommonDataMap::F()->rebind($result_array);
                            if ($map->get_filtered('success', ['Boolean', 'DefaultFalse'])) {
                                return true;
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    var_dump($e);
                    die();
                }
            }
        }
        return false;
    }

    public static function run_throw(string $captcha = null) {
        if (!static::run($captcha)) {
            \Errors\common_error::R("looks like you are bot. Bots not allowed here");
        }
    }

}
