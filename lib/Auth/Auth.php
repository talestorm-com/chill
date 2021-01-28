<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

/**
 * класс авторизации
 * @property UserInfo $user_info
 * @property int $id
 * @property Roles\IRole $role
 * @property bool $authenticated
 */
class Auth implements IAuth {

    use \common_accessors\TCommonAccess;

    const ADEBUG = true;

    /** @var Auth */
    protected static $instance;

    /** @var UserInfo */
    protected $user_info;

    //<editor-fold defaultstate="collapsed" desc="getters">
    protected function __get__user_info() {
        return $this->user_info;
    }

    protected function __get__id() {
        return $this->get_id();
    }

    protected function __get__role() {
        return $this->user_info->role;
    }

    protected function __get__authenticated() {
        return $this->is_authentificated();
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="IAuth implementation">
    public function get_id(): int {
        return $this->user_info->id ? $this->user_info->id : 0;
    }

    public function get_role(): Roles\IRole {
        return $this->user_info->role;
    }

    public function get_role_string(): string {
        return $this->user_info->role_type;
    }

    public function get_user_info(): UserInfo {
        return $this->user_info;
    }

    public function is(string $role_class): bool {
        $result = $this->user_info->is_role($role_class);

        return $result;
    }

    public function is_authentificated(): bool {
        return $this->user_info->valid;
    }

    //</editor-fold>


    protected function __construct() {
        static::$instance = $this;
        $this->reload();
    }

    protected function reload() {
        $datasource = \DataMap\HeaderDataMap::F()->exists(IAuthConsts::auth_token_field_name) ? \DataMap\HeaderDataMap::F() : \DataMap\CookieDataMap::F();
        /* @var $datasource \DataMap\AbstractDataMap */
        $token_string = $datasource->get_filtered(IAuthConsts::auth_token_field_name, ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']);
        $token_parser = TokenParser::F($token_string);
        $this->user_info = UserInfo::MC($token_parser->id);
        if (static::ADEBUG) {
           // file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "inpars", print_r($token_parser, true));
            //file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "inhdrs", print_r(\DataMap\HeaderDataMap::F(), true));
            //file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "indata", print_r(\DataMap\InputDataMap::F(), true));
        }
        $this->write_token($token_parser);
    }

    protected function write_token(TokenParser $parser = null): IAuth {
        $new_token = TokenBuilder::F($this, $parser)->token;
        \DataMap\HeaderDataMap::F()->set(IAuthConsts::auth_token_field_name, $new_token);
        \DataMap\CookieDataMap::F()->set_with_ttl(IAuthConsts::auth_token_field_name, $new_token, IAuthConsts::LIFETIME);
        \Out\Out::F()->add("auth_token", $new_token, "authorization");
        //file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "aaa", $new_token);

        return $this;
    }

    /**
     * 
     * @return IAuth
     */
    public static function F(): IAuth {
        return static::$instance ? static::$instance : new static();
    }

    public function force_login(int $user_id) {
        $ui = UserInfo::F(intval($user_id));
        if ($ui && $ui->valid) {
            $ui->store_to_mc();
            $this->user_info = $ui;
            $this->write_token();
            return;
        }
        AuthError::R("user not found");
    }

    public function force_login_s(string $user) {
        $ui = UserInfo::S($user);
        if ($ui && $ui->valid) {
            $ui->store_to_mc();
            $this->user_info = $ui;
            $this->write_token();
            return;
        }
        AuthError::R("user not found");
    }

    public function login(string $user, string $password): bool {
        $ui = UserInfo::S($user);
        if ($ui && $ui->valid) {

            if ($ui->check_password($password)) {
                $ui->store_to_mc();
                $this->user_info = $ui;
                $this->write_token();
                return true;
            }
        }
        return false;
    }

    public function login_phone(string $phone, string $password): bool {
        $phone = \Filters\FilterManager::F()->apply_chain($phone, ["PhoneMatch", "PhoneClear", "DefaultNull"]);
        $phone ? 0 : \Errors\common_error::R("invalid phone number");
        $ui = UserInfo::PHONE($phone);
        if ($ui && $ui->valid) {
            if ($ui->check_password($password)) {
                $ui->store_to_mc();
                $this->user_info = $ui;
                $this->write_token();
                return true;
            }
        }
        return false;
    }

    public function logout() {
        $this->user_info = UserInfo::F(0);
        $this->write_token();
    }

}
