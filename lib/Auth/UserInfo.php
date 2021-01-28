<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

/**
 * ?? subscriptions and owned items?
 * @property integer $id   user id
 * @property string $guid  user guid
 * @property string $login user login/email 
 * @property string $role_type string role class prefix
 * @property \Auth\Roles\IRole $role    role object (IRole implementor). By default - RoleNone
 * @property string $name  user name
 * @property string $family user family
 * @property string $eldername user eldername
 * @property string $phone  user phone
 * @property string $pass   encoded user password (hash). this value does not marshall (trick) and available on backend only
 * @property integer $instance_time  time when object whas instantiated
 * @property int $version - userInfo class version. restore from session only available if versions match
 * @property bool $valid   state of current userinfo record
 * @property bool $is_approved   is user email confirmed
 * @property \DateTime $created
 * @property \DateTime $subscription_expires
 * @property bool $subscribed
 * @property string $phone_strip stripped phone (unique)
 */
class UserInfo implements \common_accessors\IMarshall, \common_accessors\IFilterValueResolver {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var integer */
    protected $id;

    /** @var string */
    protected $guid;

    /** @var string */
    protected $login;

    /** @var string */
    protected $role_type;

    /** @var \Auth\Roles\IRole */
    protected $role;

    /** @var string */
    protected $name;

    /** @var string */
    protected $family;

    /** @var string */
    protected $eldername;

    /** @var string */
    protected $phone;

    /** @var string */
    protected $pass;

    /** @var int */
    protected $version;

    /** @var int */
    protected $instance_time;

    /** @var bool */
    protected $is_approved;

    /** @var \DateTime */
    protected $created;

    /** @var \DateTime */
    protected $subscription_expires;

    /** @var string */
    protected $phone_strip;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return integer */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__guid() {
        return $this->guid;
    }

    /** @return string */
    protected function __get__login() {
        return $this->login;
    }

    /** @return string */
    protected function __get__role_type() {
        return $this->role_type;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__family() {
        return $this->family;
    }

    /** @return string */
    protected function __get__eldername() {
        return $this->eldername;
    }

    /** @return string */
    protected function __get__phone() {
        return $this->phone;
    }

    /** @return string */
    protected function __get__pass() {
        return $this->pass;
    }

    protected function __get__version() {
        return $this->version;
    }

    protected function __get__valid() {

        return $this->id && Roles\RoleClient::is($this->role) ? true : false;
    }

    protected function __get__role() {
        return $this->role;
    }

    protected function __get__is_approved() {
        return $this->is_approved;
    }

    protected function __get__created() {
        return $this->created;
    }

    /** @return \DateTime */
    protected function __get__subscription_expires() {
        return $this->subscription_expires;
    }

    /** @return bool */
    protected function __get__subscribed() {
        return $this->subscription_expires !== null && $this->subscription_expires->getTimestamp() >= time();
    }

    /** @return string */
    protected function __get__phone_strip() {
        return $this->phone_strip;
    }

    //</editor-fold>

    /**
     * trick to avoid password leak to frontend
     * @return string
     */
    protected function t_default_marshaller_export_property_pass() {
        return '';
    }

    protected function t_default_marshaller_export_property_created() {
        return $this->created ? $this->created->format("d.m.Y H:i:s") : null;
    }

    protected function t_default_marshaller_export_property_subscription_expires() {
        return $this->subscription_expires ? $this->subscription_expires->format("d.m.Y H:i:s") : null;
    }

    /**
     * only sets version data - all initialization in init_ methods
     */
    protected function __construct() {
        $this->version = filemtime(__FILE__);
    }

    /**
     * loads user info from database by user_id
     * @param int $id
     * @return \Auth\UserInfo
     */
    protected function init(int $id): UserInfo {
        $row = \DB\DB::F()->queryRow("SELECT A.*,B.*
            FROM user A LEFT JOIN user__fields B ON(B.id=A.id)            
            WHERE A.id=:P", [":P" => intval($id)]);
        if ($row) {
            $this->import_props($row, $this);
            $this->instance_time = time();
        } else {
            $this->import_defaults();
        }
        return $this;
    }

    /**
     * loads user data from database by user login(email)
     * @param string $login
     * @return \Auth\UserInfo
     */
    protected function init_s(string $login): UserInfo {
        $row = \DB\DB::F()->queryRow("SELECT A.*,B.*
            FROM user A LEFT JOIN user__fields B ON(B.id=A.id)             
            WHERE A.login=:P", [":P" => $login]);
        if ($row) {
            $this->import_props($row, $this);
            $this->instance_time = time();
        } else {
            $this->import_defaults();
        }
        return $this;
    }

    /**
     * loads user data from database by user phone
     * @param string $login
     * @return \Auth\UserInfo
     */
    protected function init_p(string $login = null): UserInfo {
        $phone = \Filters\FilterManager::F()->apply_chain($login, ["Trim", "PhoneMatch", "PhoneClear", "DefaultNull"]);
        if ($phone) {
            $row = \DB\DB::F()->queryRow("SELECT A.*,B.*
            FROM user A LEFT JOIN user__fields B ON(B.id=A.id)             
            WHERE A.phone_strip=:P", [":P" => $phone]);
            if ($row) {
                $this->import_props($row, $this);
                $this->instance_time = time();
            } else {
                $this->import_defaults();
            }
        } else {
            $this->import_defaults();
        }
        return $this;
    }

    /**
     * stores user data into session var
     * @return \Auth\UserInfo
     */
    public function store_to_session(): UserInfo {
        \DataMap\SessionDataMap::F()->set(IAuthConsts::session_user_info_marker, ($this->__get__valid() ? $this : null));
        return $this;
    }

    public function store_to_mc(): UserInfo {
        \DataMap\MCDataMap::F()->set(sprintf("%s_%s", IAuthConsts::session_user_info_marker, intval($this->id)), ($this->__get__valid() ? serialize($this) : null));
        return $this;
    }

    /**
     * instances userinfo by id
     * @param int $id
     * @return \Auth\UserInfo
     */
    public static function F(int $id): UserInfo {
        return (new static())->init($id);
    }

    /**
     * instances userinfo by login
     * @param string $login
     * @return \Auth\UserInfo
     */
    public static function S(string $login): UserInfo {
        return (new static())->init_s($login);
    }

    /**
     * instances userinfo by phone
     * @param string $phone
     * @return \Auth\UserInfo
     */
    public static function PHONE(string $phone = null): UserInfo {
        return (new static())->init_p($phone);
    }

    /**
     * 
     * restores userinfo from session if it exists, has apropriate version and not expired
     * else - instances by id
     * @param int $id
     */
    public static function SESS(int $id): UserInfo {
        $some = \DataMap\SessionDataMap::F()->get(IAuthConsts::session_user_info_marker);
        if ($some && is_object($some) && ($some instanceof UserInfo) && $some->valid) {
            if ($some->version === filemtime(__FILE__) && $some->id === $id && $some->instance_time > (time() - IAuthConsts::REV_TIME)) {
                return $some;
            }
        }
        return static::F($id)->store_to_session();
    }

    public static function MC(int $id): UserInfo {
        $ssome = \DataMap\MCDataMap::F()->get_filtered(sprintf("%s_%s", IAuthConsts::session_user_info_marker, intval($id)), ["Trim", "NEString", "DefaultNull"]);
        if ($ssome) {
            $some = @unserialize($ssome);
            if ($some && is_object($some) && ($some instanceof UserInfo) && $some->valid) {
                if ($some->version === filemtime(__FILE__) && $some->id === $id && $some->instance_time > (time() - IAuthConsts::REV_TIME)) {
                    \DataMap\MCDataMap::F()->touch(sprintf("%s_%s", IAuthConsts::session_user_info_marker, intval($id)));
                    return $some;
                }
            }
        }
        return static::F($id)->store_to_mc();
    }

    protected function import_defaults() {
        return $this->import_props([], $this);
    }

    //<editor-fold defaultstate="collapsed" desc="common_import">
    protected function t_common_import_get_filter_params() {
        return [];
    }

    protected function t_common_import_get_filters() {
        \Filters\classes\IntMore0Filter::class;
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'login' => ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull'],
            'pass' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'guid' => ['Strip', 'Trim', 'NEString', 'GUID', 'DefaultNull'],
            'role' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'family' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'eldername' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'phone' => ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull'],
            'phone_strip' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'is_approved' => ['Boolean', 'DefaultFalse'],
            'created' => ['DateMatch', 'DefaultNull'],
            'locked' => ['Boolean', 'DefaultFalse'],
            'subscription_expires' => ["DateMatch", "DefaultNull"],
        ];
    }

    public function resolve_value(string $prop, \Filters\Value $value) {
        return null;
    }

    protected function t_common_import_after_import() {
        $this->role_type = \Helpers\Helpers::NEString($this->role, 'none');
        $this->role = Roles\AbstractRole::FT($this->role_type);
    }

    //</editor-fold>

    public function is_role(string $role_class): bool {
        return $this->role->is_a($role_class);
    }

    public function check_password(string $pass_to_test): bool {
        $apass = explode(":", \Helpers\Helpers::NEString($this->pass, ''));
        if (count($apass) === 2) {
            if ($apass[0] === 'MD') {
                $check = md5($pass_to_test);
                if (strcasecmp($check, $apass[1]) === 0) {
                    return true;
                }
            } else {
                $ptc = "{$apass[0]}{$apass[0]}{$pass_to_test}{$apass[0]}{$apass[0]}";
                if (!\OpenSSL\OpenSSL::F('passwords', 1024)->checkSign($ptc, $apass[1])) {
                    $random = mt_rand(0, 200000);
                    $pts = "{$random}{$random}{$pass_to_test}{$random}{$random}";
                    $sign = \OpenSSL\OpenSSL::F('passwords', 1024)->sign($pts);
                    $valid_password_key = "{$random}:{$sign}";
                    //\Out\Out::F()->add("password", $valid_password_key, 'debug');
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    public static function encrypt_password(string $password): string {
        $random = mt_rand(0, 200000);
        $pte = "{$random}{$random}{$password}{$random}{$random}";
        $sign = \OpenSSL\OpenSSL::F('passwords', 1024)->sign($pte);
        return "{$random}:{$sign}";
    }

    public function generate_sequrity_hash() {
        $ssl = \OpenSSL\OpenSSL::F("pass_restore", 512);
        return $ssl->sign($this->get_data_for_sign());
    }

    protected function get_data_for_sign() {
        $data_array = [
            $this->id,
            $this->guid,
            $this->created ? $this->created->format("YmdHis") : 'N',
            $this->pass
        ];
        return implode("*", $data_array);
    }

    public function check_sequrity_hash(string $hash) {
        $ssl = \OpenSSL\OpenSSL::F("pass_restore", 512);
        if ($ssl->checkSign($this->get_data_for_sign(), $hash)) {
            return true;
        }
        return false;
    }

    public function create_confirm_hash() {
        $ssl = \OpenSSL\OpenSSL::F("user_confirm", 512);
        return $ssl->sign((string) $this->guid);
    }

    public function check_confirm_hash(string $hash) {
        $ssl = \OpenSSL\OpenSSL::F("user_confirm", 512);
        if ($ssl->checkSign((string) $this->guid, $hash)) {
            return true;
        }
        return false;
    }

}
