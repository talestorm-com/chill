<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

/**
 * @property bool $valid
 * @property integer $user_id
 * @property int $id
 * @property string $user_role
 * @property integer $create_time 
 * @property string $signature 
 * @property integer $lifetime время жизни токена
 * @property string $dev_uid идентификатор устройства
 */
class TokenParser {

    use \common_accessors\TCommonAccess;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var integer */
    protected $user_id;

    /** @var string */
    protected $user_role;

    /** @var integer */
    protected $create_time;

    /** @var string */
    protected $signature;

    /** @var integer */
    protected $lifetime;

    /** @var string */
    protected $dev_uid;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return bool */
    protected function __get__valid() {
        return $this->user_id ? true : false;
    }

    /** @return integer */
    protected function __get__user_id() {
        return $this->user_id;
    }

    /** @return string */
    protected function __get__user_role() {
        return $this->user_role;
    }

    /** @return integer */
    protected function __get__create_time() {
        return $this->create_time;
    }

    /** @return string */
    protected function __get__signature() {
        return $this->signature;
    }

    protected function __get__id() {
        return intval($this->user_id);
    }

    /** @return integer */
    protected function __get__lifetime() {
        return $this->lifetime;
    }

    /** @return string */
    protected function __get__dev_uid() {
        return $this->dev_uid;
    }

    //</editor-fold>
    protected $log = [];

    protected function ramlog(string $message) {
        
    }

    protected function __construct(string $token) {
        $this->reset();
        $token = \Helpers\Helpers::NEString($token, '');

        $atoken = explode(".", $token);
        if (count($atoken) === 5) {
            $this->user_id = intval($atoken[0]);
            $this->user_role = trim($atoken[1]);
            $this->create_time = intval($atoken[2]);
            $this->lifetime = intval($atoken[3]);
            $this->signature = trim($atoken[4]);
            $this->dev_uid = static::get_client_dev_uid();
            if (\DataMap\InputDataMap::F()->get_filtered("debug_token", ['Boolean', 'DefaultFalse'])) {
                \Out\Out::F()->add("parsed_token", [
                    'id' => $this->id,
                    'role' => $this->user_role,
                    'create_time' => $this->create_time,
                    'lifetime' => $this->lifetime,
                    'dev_uid' => $this->dev_uid,
                    'signature' => $this->signature
                ]);
            }
            if ($this->user_id) {
                $now = time();
                if (($this->lifetime === IAuthConsts::LIFETIME_UNSPECIFIED) || (($now - $this->lifetime) < $this->create_time)) {
                    //check_signature
                    $data_to_check = implode("*", [$this->user_id, $this->user_role, $this->create_time, $this->lifetime, $this->dev_uid]);
                    if (\OpenSSL\OpenSSL::F("auth_token", 512)->checkSign($data_to_check, $this->signature)) {
                        return $this->ready();
                    } elseif (\DataMap\InputDataMap::F()->get_filtered("debug_token", ['Boolean', 'DefaultFalse'])) {
                        \Out\Out::F()->add("token_step", "invalid signature");
                    }
                } elseif (\DataMap\InputDataMap::F()->get_filtered("debug_token", ['Boolean', 'DefaultFalse'])) {
                    \Out\Out::F()->add("token_step", "token timeout");
                }
            } else {
                if (\DataMap\InputDataMap::F()->get_filtered("debug_token", ['Boolean', 'DefaultFalse'])) {
                    \Out\Out::F()->add("token_step", "no user_id");
                }
            }
        }
        $this->reset();
    }

    protected function ready() {
        return $this;
    }

    protected function reset() {
        $this->user_id = 0;
        $this->user_role = null;
        $this->create_time = null;
        $this->signature = null;
        $this->lifetime = IAuthConsts::LIFETIME;
        $this->dev_uid = null;
        return $this;
    }

    public static function F(string $token): TokenParser {
        return new static($token);
    }

    public static function get_client_dev_uid(): string {
        $result = null;
        $result = \DataMap\HeaderDataMap::F()->get_filtered(IAuthConsts::auth_token_devuid_field, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$result) {
            $result = \DataMap\HeaderDataMap::F()->get_filtered("user-agent", ["Trim", "NEString", "DefaultNull"]);
        }
        return $result ? $result : "u";
    }

}
