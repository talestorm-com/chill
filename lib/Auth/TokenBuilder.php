<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

/**
 * @property string $token
 */
class TokenBuilder {

    use \common_accessors\TCommonAccess;

    protected $token = null;

    protected function __get__token() {
        return $this->token;
    }

    protected function __construct(IAuth $b, TokenParser $p = null) {
//        $this->user_id = intval($atoken[0]);
//            $this->user_role = trim($atoken[1]);
//            $this->create_time = intval($atoken[2]);
//            $this->lifetime = intval($atoken[3]);
//            $this->signature = trim($atoken[4]);
        $token_data = $b->is_authentificated() ? $this->build_token_data($b, $p) : null;
        if ($token_data) {
            $data_to_sign = implode("*", $token_data);
            $signature = \OpenSSL\OpenSSL::F("auth_token", 512)->sign($data_to_sign);
            $token_data[count($token_data)-1] = $signature; 
            $this->token = implode(".", $token_data);
        } else {
            $this->token = 'invalid';
        }
    }

    protected function build_token_data(IAuth $b, TokenParser $p = null) {
        $lifetime = \DataMap\InputDataMap::F()->get_filtered("auth_token_lifetime", ["Int","DefaultNull"]);
        if ($lifetime === null) {
            $lifetime = $p ? $p->lifetime : null;
        }
        if ($lifetime === null) {
            $lifetime = IAuthConsts::LIFETIME;
        }
        return [
            $b->get_id(),
            $b->get_role_string(),
            time(),
            $lifetime,
            TokenParser::get_client_dev_uid()
        ];
    }

    public static function F(IAuth $b): TokenBuilder {
        return new static($b);
    }

}
