<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

class AuthController extends AbstractFrontendController {

    const CSRF_LOGIN_ENABLED = true;

    protected function actionIndex() {
        $this->out->add_css("/assets/css/layouts/login.css", -10);
        $this->out->add('login_return_url', \Helpers\Helpers::NEString(\DataMap\GPDataMap::F()->get_filtered('return_url', ['Trim', 'NEString', 'DefaultNull']), "/"));
        $this->render_view('login', 'default');
    }

    protected function API_check_memcached() {
        $start = microtime(true);
        $mc = \DataMap\MCDataMap::F();
        echo "mc_instanced\n";
        echo "checking for key \n";
        echo $mc->exists(__METHOD__) ? "exists\n" : "not exists\n";
        if (!$mc->exists(__METHOD__)) {
            echo "adding\n";
            $mc->set_with_timeout(__METHOD__, time(), 10);
        } else {
            $mc->touch(__METHOD__, 10);
        }
        echo "reading \n";
        echo "{$mc->get(__METHOD__)}\n";
        $end = microtime(true);
        echo (($end - $start) * 1000) . " ms";
        die();
    }

    protected function actionRestore() {
        $user = $this->GP->get_filtered('user', ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull']);
        $validate = $this->GP->get_filtered('validate', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if ($user && $validate) {
            $user_info = \Auth\UserInfo::S($user);
            if ($user_info && $user_info->valid) {
                if ($user_info->check_sequrity_hash($validate)) {
                    $new_password = \Helpers\Helpers::mk_password();
                    $new_password_encrypted = \Auth\UserInfo::encrypt_password($new_password);
                    $b = \DB\SQLTools\SQLBuilder::F();
                    $b->push("UPDATE user SET pass=:Ppass,is_approved=1 WHERE id=:Pid;");
                    $b->push_params([":Ppass" => $new_password_encrypted, ":Pid" => $user_info->id]);
                    $b->execute_transact();
                    \CommonTasks\TaskRestorePasswordPhaseTwo::mk_params()->run([
                        'user_login' => $user_info->login,
                        'new_password' => $new_password,
                    ]);
                    $this->render_view('front/layout', 'restore_result');
                    die();
                }
            }
        }
        \Router\NotFoundError::R("not found");
    }

    protected function API_confirm_restore() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
            'user' => ['Strip', 'Trim', 'NEString', 'EmailMatch'],
            'validate' => ['Strip', 'Trim', 'NEString']
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        $user = $data['user'];
        $validate = $data['validate'];
        if ($user && $validate) {
            $user_info = \Auth\UserInfo::S($user);
            if ($user_info && $user_info->valid) {
                if ($user_info->check_sequrity_hash($validate)) {
                    $new_password = \Helpers\Helpers::mk_password();
                    $new_password_encrypted = \Auth\UserInfo::encrypt_password($new_password);
                    $b = \DB\SQLTools\SQLBuilder::F();
                    $b->push("UPDATE user SET pass=:Ppass,is_approved=1 WHERE id=:Pid;");
                    $b->push_params([":Ppass" => $new_password_encrypted, ":Pid" => $user_info->id]);
                    $b->execute_transact();
                    \CommonTasks\TaskRestorePasswordPhaseTwo::mk_params()->run([
                        'user_login' => $user_info->login,
                        'new_password' => $new_password,
                    ]);
                    $this->out->add("new_password", $new_password);
                    return;
                }
            }
        }
        \Errors\common_error::R("error on restore confirmation");
    }

    protected function actionLogin() {
        $this->actionIndex();
    }

    protected function actionDummy() {
        die('success');
    }

    protected function actionLogout() {
        try {
            \Auth\Auth::F()->logout();
        } catch (\Throwable $e) {
            
        }
        if (!headers_sent()) {
            \Router\Router::F()->redirect("/", 302);
        } else {
            die("logout success");
        }
    }

    protected function API_auth() {
        $user_email = \DataMap\InputDataMap::F()->get_filtered("login", ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull']);
        $user_phone = \DataMap\InputDataMap::F()->get_filtered("login", ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull']);
        if (static::CSRF_LOGIN_ENABLED) {
            $csrf = \DataMap\InputDataMap::F()->get_filtered('csrf', ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']);
            $this->check_csrf_throw($csrf, 'login', false);
        }
        ($user_email || $user_phone) ? 0 : \Errors\common_error::R("user email or phone required");
        $pass = \DataMap\InputDataMap::F()->get_filtered("password", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $pass ? 0 : \Errors\common_error::R("password required");
        ($user_email || $user_phone) && $pass ? false : \Auth\AuthError::R("invalid request");
        if ($user_email) {
            if (!$this->auth->login($user_email, $pass)) {
                \Auth\AuthError::R("invalid login");
            }
        } else if ($user_phone) {
            if (!$this->auth->login_phone($user_phone, $pass)) {
                \Auth\AuthError::R("invalid login");
            }
        }
        $this->out->add('user_info', $this->auth->user_info->marshall());
        if (static::CSRF_LOGIN_ENABLED) {
            $csrf = \DataMap\InputDataMap::F()->get_filtered('csrf', ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']);
            \Helpers\Helpers::csrf_remove($csrf, 'login');
        }
    }

    protected function API_login() {
        $this->API_auth();
    }

    protected function API_register_chill() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
            "family" => ["Strip", "Trim", "NEString", "DefaultEmptyString"], //Strip,Trim,NEString
            "name" => ["Strip", "Trim", "NEString",], //Strip,Trim,NEString
            "eldername" => ["Strip", "Trim", "NEString", "DefaultEmptyString"], //Strip,Trim,NEString,DefaultEmptyString
            "login" => ["Strip", "Trim", "NEString", "EmailMatch"], //Strip,Trim,NEString,EmailMatch            
            "phone" => ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull'],
            "birth_date" => ["Strip", 'Trim', 'NEString', 'DateMatch'],
            "password" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString    
            "csrf" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString    
            "captcha" => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        if (mb_strlen($data["password"], "UTF-8") < 6) {
            \Errors\common_error::R("password min length is 6");
        }
        \RecaptchaV3\recaptcha::run_throw($data['captcha']);
        $this->check_csrf_throw($data['csrf'], 'signup', false); // до - нельзя, они одноразовые            

        $test = \Auth\UserInfo::S($data['login']);
        if ($test && $test->valid) {
            \Errors\common_error::R("Этот email уже зарегистрирован");
        }
        $test_phone = \Auth\UserInfo::PHONE($data["phone"]);
        if ($test_phone && $test_phone->valid) {
            \Errors\common_error::R("Этот номер телефона уже зарегистрирован");
        }


        $b = \DB\SQLTools\SQLBuilder::F();
        $new_pass_encrypted = \Auth\UserInfo::encrypt_password($data['password']);
        $tn = "@a" . md5(__METHOD__);
        $start_money_value = \PresetManager\PresetManager::F()->get_filtered('START_MONEY_VALUE', ['IntMore0', 'Default0']);
        $referal_link = \Referal\ReferalLink::F();
        if ($referal_link->valid) {
            $start_money_value_t = \PresetManager\PresetManager::F()->get_filtered('REFERAL_BONUS_NEW_USER', ['IntMore0', 'Default0']);
            $start_money_value = max([$start_money_value, $start_money_value_t]);
        }
        $b->push("INSERT INTO user (guid,login,pass,role,is_approved,news,created,phone_strip,birth_date)
            VALUES(UUID(),:P{$b->c}login,:P{$b->c}pass,:P{$b->c}role,0,1,NOW(),:P{$b->c}strip_phone,:P{$b->c}birth);
                SET {$tn}=LAST_INSERT_ID();
             INSERT INTO user__fields (id,name,family,eldername,phone)       
             VALUES({$tn},:P{$b->c}name,:P{$b->c}family,:P{$b->c}eldername,:P{$b->c}phone);       
             INSERT INTO user__wallet(id,money) VALUES({$tn},:P{$b->c}moneymoney) ON DUPLICATE KEY UPDATE money=money+VALUES(money);    
            ");
        if ($referal_link->valid) {
            $referal_reward = \PresetManager\PresetManager::F()->get_filtered('REFERAL_BONUS_OLD_USER', ['IntMore0', 'Default0']);
            if ($referal_reward) {
                $b->push("INSERT INTO user__wallet(id,money)
                    SELECT id,:P{$b->c}referal_reward FROM user
                        WHERE id=:P{$b->c}referal_id
                    ON DUPLICATE KEY UPDATE money=money+VALUES(money);        
                    ")->push_params([
                    ":P{$b->c}referal_reward" => $referal_reward,
                    ":P{$b->c}referal_id" => $referal_link->referal_id
                ]);
            }
        }
        $b->push_params([
            ":P{$b->c}login" => $data["login"],
            ":P{$b->c}pass" => $new_pass_encrypted,
            ":P{$b->c}name" => $data['name'],
            ":P{$b->c}family" => $data['family'],
            ":P{$b->c}eldername" => $data["eldername"],
            ":P{$b->c}phone" => $data["phone"],
            ":P{$b->c}birth" => $data["birth_date"]->format('Y-m-d 00:00:00'),
            ":P{$b->c}role" => "client",
            ":P{$b->c}strip_phone" => \Filters\FilterManager::F()->apply_chain($data["phone"], ["PhoneClear", "DefaultNull"]),
            ":P{$b->c}moneymoney" => $start_money_value,
        ]);

        $rid = $b->execute_transact($tn);

        $this->auth->force_login($rid);
        //\CommonTasks\TaskNewUserRegistred::mk_params()->run(["user_id" => $rid]);
        $this->auth->authenticated ? $this->API_info() : 0;
        \Helpers\Helpers::csrf_remove($data['csrf'], 'signup');
    }

    protected function API_register_n() {

        //  file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "inhdrs", print_r(\DataMap\HeaderDataMap::F(), true));
        //  file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "indata", print_r(\DataMap\InputDataMap::F(), true));
        //  file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "indataraw",  @file_get_contents("php://input"));
        $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
            "family" => ["Strip", "Trim", "NEString", "DefaultEmptyString"], //Strip,Trim,NEString
            "name" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString
            "eldername" => ["Strip", "Trim", "NEString", "DefaultEmptyString"], //Strip,Trim,NEString,DefaultEmptyString
            "login" => ["Strip", "Trim", "NEString", "EmailMatch"], //Strip,Trim,NEString,EmailMatch
            "phone" => ["Strip", "Trim", "NEString", "PhoneMatch", "DefaultNull"], //Strip,Trim,NEString,PhoneMatch                                          
            "password" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString    
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        if (mb_strlen($data["password"], "UTF-8") < 6) {
            \Errors\common_error::R("password min length is 6");
        }
        $test = \Auth\UserInfo::S($data['login']);
        if ($test && $test->valid) {
            \Errors\common_error::R("login exists");
        }
        $test_phone = \Auth\UserInfo::PHONE($data["phone"]);
        if ($test_phone && $test_phone->valid) {
            \Errors\common_error::R("phone exists");
        }
        $b = \DB\SQLTools\SQLBuilder::F();
        $new_pass_encrypted = \Auth\UserInfo::encrypt_password($data['password']);
        $tn = "@a" . md5(__METHOD__);
        $b->push("INSERT INTO user (guid,login,pass,role,is_approved,news,created,phone_strip)
            VALUES(UUID(),:P{$b->c}login,:P{$b->c}pass,:P{$b->c}role,0,1,NOW(),:P{$b->c}strip_phone);
                SET {$tn}=LAST_INSERT_ID();
             INSERT INTO user__fields (id,name,family,eldername,phone)       
             VALUES({$tn},:P{$b->c}name,:P{$b->c}family,:P{$b->c}eldername,:P{$b->c}phone);             
            ");
        $b->push_params([
            ":P{$b->c}login" => $data["login"],
            ":P{$b->c}pass" => $new_pass_encrypted,
            ":P{$b->c}news" => $data['news'] ? 1 : 0,
            ":P{$b->c}name" => $data['name'],
            ":P{$b->c}family" => $data['family'],
            ":P{$b->c}eldername" => $data["eldername"],
            ":P{$b->c}phone" => $data["phone"],
            ":P{$b->c}role" => \DataMap\InputDataMap::F()->get("trainer", ["Boolean", "DefaultFalse"]) ? "trainer" : "client",
            ":P{$b->c}strip_phone" => \Filters\FilterManager::F()->apply_chain($data["phone"], ["PhoneClear", "DefaultNull"]),
        ]);
        $rid = $b->execute_transact($tn);
        $this->auth->force_login($rid);
        \CommonTasks\TaskNewUserRegistred::mk_params()->run(["user_id" => $rid]);
        $this->auth->authenticated ? $this->API_info() : 0;
    }

    protected function API_restore_o() {
        $user = \DataMap\GPDataMap::F()->get_filtered("login", ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull']);
        $user ? 0 : \Errors\common_error::R("invalid request");
        $user_info = \Auth\UserInfo::S($user);
        if ($user_info->valid) {
            \CommonTasks\TaskRestorePasswordPhaseOne::mk_params()->run(['user_login' => $user_info->login]);
        } else {
            \Errors\common_error::R("user not found");
        }
    }

    protected function API_restore() {
        $user_email = \DataMap\InputDataMap::F()->get_filtered("login", ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull']);
        $user_phone = \DataMap\InputDataMap::F()->get_filtered("login", ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull']);
        ($user_email || $user_phone) ? 0 : \Errors\common_error::R("user email or phone required");
        if ($user_email) {
            $user_info = \Auth\UserInfo::S($user_email);
            if ($user_info->valid) {
                \CommonTasks\TaskRestorePasswordPhaseOne::mk_params()->run(['user_login' => $user_info->login]);
            } else {
                \Errors\common_error::R("user not found");
            }
        } else if ($user_phone) {
            $user_info = \Auth\UserInfo::PHONE($user_phone);
            if ($user_info->valid) {
                \CommonTasks\TaskRestorePasswordPhaseOne::mk_params()->run(['user_login' => $user_info->login]);
            } else {
                \Errors\common_error::R("user not found");
            }
        }
    }

    protected function API_register() {
        $raw_data = $this->GP->get_filtered("data", ["Trim", "NEString", "JSONString", "DefaultNull"]);
        $raw_data && is_array($raw_data) ? 0 : \Errors\common_error::R("invalid request");
        $data = \Filters\FilterManager::F()->apply_filter_array($raw_data, [
            "family" => ["Strip", "Trim", "NEString", "DefaultEmptyString"], //Strip,Trim,NEString
            "name" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString
            "eldername" => ["Strip", "Trim", "NEString", "DefaultEmptyString"], //Strip,Trim,NEString,DefaultEmptyString
            "login" => ["Strip", "Trim", "NEString", "EmailMatch"], //Strip,Trim,NEString,EmailMatch
            "phone" => ["Strip", "Trim", "NEString", "PhoneMatch"], //Strip,Trim,NEString,PhoneMatch
            "password" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString
            //"repassword" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString            
            "news" => ["Boolean", "DefaultTrue"], //Boolean,DefaultTrue
                //"apd" => ["Boolean", "DefaultFalse"], //Boolean,DefaultFalse
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        if (mb_strlen($data["password"], "UTF-8") < 6) {
            \Errors\common_error::R("password min length is 6");
        }
        $data["repassword"] = $data['password'];
        if ($data["password"] !== $data['repassword']) {
            \Errors\common_error::R("passwords dont match");
        }
        $test = \Auth\UserInfo::S($data['login']);
        if ($test && $test->valid) {
            \Errors\common_error::R("login_exists");
        }
        $test = \Auth\UserInfo::PHONE($data['phone']);
        if ($test && $test->valid) {
            \Errors\common_error::R("phone_exists");
        }
        $b = \DB\SQLTools\SQLBuilder::F();
        $new_pass_encrypted = \Auth\UserInfo::encrypt_password($data['password']);
        $tn = "@a" . md5(__METHOD__);
        $b->push("INSERT INTO user (guid,login,pass,role,is_approved,news,created)
            VALUES(UUID(),:P{$b->c}login,:P{$b->c}pass,:P{$b->c}role,0,:P{$b->c}news,NOW());
                SET {$tn}=LAST_INSERT_ID();
             INSERT INTO user__fields (id,name,family,eldername,phone)       
             VALUES({$tn},:P{$b->c}name,:P{$b->c}family,:P{$b->c}eldername,:P{$b->c}phone);             
            ");
        $b->push_params([
            ":P{$b->c}login" => $data["login"],
            ":P{$b->c}pass" => $new_pass_encrypted,
            ":P{$b->c}role" => "client",
            ":P{$b->c}news" => $data['news'] ? 1 : 0,
            ":P{$b->c}name" => $data['name'],
            ":P{$b->c}family" => $data['family'],
            ":P{$b->c}eldername" => $data["eldername"],
            ":P{$b->c}phone" => $data["phone"],
                //":P{$b->c}search_name" => trim("{$data["family"]} {$data["name"]} {$data["eldername"]}"),
                //":P{$b->c}search_phone" => preg_replace("/\D/", "", $data["phone"]),
        ]);
        $rid = $b->execute_transact($tn);
        if ($rid) {
            $this->auth->force_login($rid);
        }
        $this->out->add("rid", $rid);
        \Content\RequestProfile\Async\NewUserTask::mk_params()->add("id", $rid)->run();
    }

    protected function API_register2() {
        $raw_data = $this->GP->get_filtered("data", ["Trim", "NEString", "JSONString", "DefaultNull"]);
        $raw_data && is_array($raw_data) ? 0 : \Errors\common_error::R("invalid request");
        $data = \Filters\FilterManager::F()->apply_filter_array($raw_data, [
            "family" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString
            "name" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString
            "eldername" => ["Strip", "Trim", "NEString", "DefaultEmptyString"], //Strip,Trim,NEString,DefaultEmptyString
            "email" => ["Strip", "Trim", "NEString", "EmailMatch"], //Strip,Trim,NEString,EmailMatch
            "phone" => ["Strip", "Trim", "NEString", "PhoneMatch", "DefaultNull"], //Strip,Trim,NEString,PhoneMatch
            "password" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString
            "repassword" => ["Strip", "Trim", "NEString"], //Strip,Trim,NEString
            "role" => ["Strip", "Trim", "NEString"], //Boolean,DefaultFalse
            "news" => ["Boolean", "DefaultTrue"], //Boolean,DefaultTrue
            "apd" => ["Boolean", "DefaultTrue"], //Boolean,DefaultFalse
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);

        if (mb_strlen($data["password"], "UTF-8") < 6) {
            \Errors\common_error::R("password min length is 6");
        }
        if ($data["password"] !== $data['repassword']) {
            \Errors\common_error::R("passwords dont match");
        }
        if (!$data['apd']) {
            \Errors\common_error::R("personal data access is required");
        }
        $test = \Auth\UserInfo::S($data['email']);
        if ($test && $test->valid) {
            \Errors\common_error::R("login_exists");
        }
        $test = \Auth\UserInfo::PHONE($data['phone']);
        if ($test && $test->valid) {
            \Errors\common_error::R("phone_exists");
        }
        $b = \DB\SQLTools\SQLBuilder::F();
        $roles = ["client" => 'client', 'trainer' => 'trainer', 'hole' => 'hole'];
        $role = array_key_exists($data['role'], $roles) ? $roles[$data['role']] : null;
        $role ? 0 : \Errors\common_error::R("unknown user role");

        $new_pass_encrypted = \Auth\UserInfo::encrypt_password($data['password']);
        $tn = "@a" . md5(__METHOD__);
        $b->push("INSERT INTO user (guid,login,pass,role,is_approved,news,created)
            VALUES(UUID(),:P{$b->c}login,:P{$b->c}pass,:P{$b->c}role,0,:P{$b->c}news,NOW());
                SET {$tn}=LAST_INSERT_ID();
             INSERT INTO user__fields (id,name,family,eldername,phone)       
             VALUES({$tn},:P{$b->c}name,:P{$b->c}family,:P{$b->c}eldername,:P{$b->c}phone);             
            ");
        $b->push_params([
            ":P{$b->c}login" => $data["email"],
            ":P{$b->c}pass" => $new_pass_encrypted,
            ":P{$b->c}role" => $role,
            ":P{$b->c}news" => $data['news'] ? 1 : 0,
            ":P{$b->c}name" => $data['name'],
            ":P{$b->c}family" => $data['family'],
            ":P{$b->c}eldername" => $data["eldername"],
            ":P{$b->c}phone" => $data["phone"],
                //":P{$b->c}search_name" => trim("{$data["family"]} {$data["name"]} {$data["eldername"]}"),
                //":P{$b->c}search_phone" => preg_replace("/\D/", "", $data["phone"]),
        ]);
        $rid = $b->execute_transact($tn);
        if ($rid) {
            $this->auth->force_login($rid);
        }
        \CommonTasks\TaskNewUserRegistred::mk_params()->run(["user_id" => $rid]);
    }

    protected function actionConfirm() {
        $id = $this->GP->get_filtered("id", ["IntMore0", "DefaultNull"]);
        $validate = $this->GP->get_filtered("validate", ["Trim", "NEString", "DefaultNull"]);
        if ($id && $validate) {
            $user_info = \Auth\UserInfo::F($id);
            if ($user_info && $user_info->valid) {
                if ($user_info->check_confirm_hash($validate)) {
                    $b = \DB\SQLTools\SQLBuilder::F();
                    $b->push("UPDATE user SET is_approved=1 WHERE id=:Pid;");
                    $b->push_params([":Pid" => $user_info->id]);
                    $b->execute_transact();
                    $this->render_view('front/layout', 'approve_result');
                    die();
                }
            }
        }
        \Router\NotFoundError::R("not found");
    }

    protected function API_confirm() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
            'id' => ["IntMore0"],
            'validate' => ["Trim", "NEString"],
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        $user_info = \Auth\UserInfo::F($data["id"]);
        if ($user_info && $user_info->valid) {
            if ($user_info->check_confirm_hash($data["validate"])) {
                $b = \DB\SQLTools\SQLBuilder::F();
                $b->push("UPDATE user SET is_approved=1 WHERE id=:Pid;");
                $b->push_params([":Pid" => $user_info->id]);
                $b->execute_transact();
                return;
            }
        }
        \Errors\common_error::R("account validation error");
    }

    protected function API_add_favorite() {
        if (!$this->auth->is_authentificated()) {
            \Errors\common_error::R("login_required");
        }
        $product_id = $this->GP->get_filtered("favorite_id", ["IntMore0", "DefaultNull"]);
        if ($product_id) {
            \DB\DB::F()->exec("INSERT INTO user__favorite (user_id,product_id,created) VALUES(:P,:PP,NOW()) ON DUPLICATE KEY UPDATE created=NOW();", [":P" => $this->auth->id, ":PP" => $product_id]);
        }
    }

    protected function API_remove_favorite() {
        if ($this->auth->is_authentificated()) {
            $product_id = $this->GP->get_filtered("favorite_id", ["IntMore0", "DefaultNull"]);
            if ($product_id) {
                \DB\DB::F()->exec("DELETE FROM user__favorite WHERE user_id=:P AND product_id=:PP;", [":P" => $this->auth->id, ":PP" => $product_id]);
            }
        }
    }

    protected function API_info() {
        $this->auth->authenticated ? 0 : \Auth\AuthError::R(\Auth\AuthError::NOT_AUTHORIZED);
        $this->out->add('user_info', $this->auth->user_info->marshall());
    }

    public function actionSocial_tw() {
        \HybridAuth\HYAUTH::F();
        $config = [
            'callback' => \Hybridauth\HttpClient\Util::getCurrentUrl(),
            'keys' => [
                'key' => \PresetManager\PresetManager::F()->get_filtered("TWITTER_KEY", ['Trim', 'NEString', 'DefaultEmptyString']), // 'your-twitter-consumer-key',
                'secret' => \PresetManager\PresetManager::F()->get_filtered("TWITTER_SECRET", ['Trim', 'NEString', 'DefaultEmptyString']), //'your-twitter-consumer-secret'
            ],
        ];

        try {
            $twitter = new \Hybridauth\Provider\Twitter($config);
            $twitter->authenticate();
            $accessToken = $twitter->getAccessToken();
            $userProfile = $twitter->getUserProfile();
            $apiResponse = $twitter->apiRequest('statuses/home_timeline.json');
            var_dump($userProfile);
            die();
        } catch (\Exception $e) {
            echo 'Oops, we ran into an issue! ' . $e->getMessage();
        }
    }

    protected function link_profile_with_web_image(int $user_id, string $image_url) {
        $curl = curl_init($image_url);
        curl_setopt_array($curl, [
            CURLOPT_HTTPGET => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $image_data = curl_exec($curl);
        if ($image_data) {
            $encoded_image_data = base64_encode($image_data);
            try {
                $fake_file = new \DataMap\FakeUploadedFile($encoded_image_data);
                $filemap = \DataMap\FileMap::F(); /* @var $filemap \DataMap\FileMap */
                $filemap->add_fake($fake_file);
                \ImageFly\ImageFly::F()->handle_upload_manual("avatar", "{$user_id}", md5("avatar"));
            } catch (\Throwable $e) {
                
            }
        }
    }

    /**
     * 
     * @param string $domain
     * @param string $id
     * @param string $email
     * @param string $name
     * @param string $photo_url
     * @param bool $created
     * @return \Auth\UserInfo
     */
    protected function get_user_info_by_social(string $domain, string $id, string $email, string $name, string $photo_url = null, bool &$xcreated = null) {
        $created = false;
        $user_id = \DB\DB::F()->queryScalari("SELECT user_id FROM chill__user_social WHERE domain=:P AND social_id=:PP", [":P" => $domain, ":PP" => $id]);
        if (!$user_id) {
            try {
                $test = \Auth\UserInfo::S($email);
                if ($test && $test->valid) {
                    $user_id = $test->id;
                    \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO chill__user_social(domain,social_id,user_id)
                        VALUES(:Pd,:Ps,:Pi) ON DUPLICATE KEY UPDATE domain=VALUES(domain);
                        ")->push_params([
                        ":Pd" => $domain,
                        ":Ps" => $id,
                        ":Pi" => $user_id,
                    ])->execute_transact();
                }
            } catch (\Throwable $e) {
                throw $e;
                $user_id = null;
            }
        }
        if (!$user_id) {
            $start_money_value = \PresetManager\PresetManager::F()->get_filtered('START_MONEY_VALUE', ['IntMore0', 'Default0']);
            $referal_link = \Referal\ReferalLink::F();
            if ($referal_link->valid) {
                $start_money_value_t = \PresetManager\PresetManager::F()->get_filtered('REFERAL_BONUS_NEW_USER', ['IntMore0', 'Default0']);
                $start_money_value = max([$start_money_value, $start_money_value_t]);
            }
            $b = \DB\SQLTools\SQLBuilder::F();
            $password = md5(implode('', [__FILE__, microtime(true)]));
            $new_pass_encrypted = \Auth\UserInfo::encrypt_password($password);
            $tn = "@a" . md5(__METHOD__);
            $b->push("INSERT INTO user (guid,login,pass,role,is_approved,news,created,phone_strip,birth_date)
            VALUES(UUID(),:P{$b->c}login,:P{$b->c}pass,:P{$b->c}role,0,1,NOW(),:P{$b->c}strip_phone,:P{$b->c}birth);
                SET {$tn}=LAST_INSERT_ID();
             INSERT INTO user__fields (id,name,family,eldername,phone)       
             VALUES({$tn},:P{$b->c}name,:P{$b->c}family,:P{$b->c}eldername,:P{$b->c}phone); 
             INSERT INTO user__wallet(id,money) VALUES({$tn},:P{$b->c}moneymoney) ON DUPLICATE KEY UPDATE money=money+VALUES(money);  
             INSERT INTO chill__user_social(domain,social_id,user_id)
                        VALUES(:P{$b->c}domain,:P{$b->c}social_id,{$tn}) ON DUPLICATE KEY UPDATE domain=VALUES(domain)                 
            ");
            if ($referal_link->valid) {
                $referal_reward = \PresetManager\PresetManager::F()->get_filtered('REFERAL_BONUS_OLD_USER', ['IntMore0', 'Default0']);
                if ($referal_reward) {
                    $b->push("INSERT INTO user__wallet(id,money)
                    SELECT id,:P{$b->c}referal_reward FROM user
                        WHERE id=:P{$b->c}referal_id
                    ON DUPLICATE KEY UPDATE money=money+VALUES(money);        
                    ")->push_params([
                        ":P{$b->c}referal_reward" => $referal_reward,
                        ":P{$b->c}referal_id" => $referal_link->referal_id
                    ]);
                }
            }
            $b->push_params([
                ":P{$b->c}login" => $email,
                ":P{$b->c}pass" => $new_pass_encrypted,
                ":P{$b->c}name" => $name,
                ":P{$b->c}family" => "",
                ":P{$b->c}eldername" => "",
                ":P{$b->c}phone" => null,
                ":P{$b->c}birth" => '2001-01-01 00:00:00',
                ":P{$b->c}role" => "client",
                ":P{$b->c}strip_phone" => null,
                ":P{$b->c}moneymoney" => $start_money_value,
                ":P{$b->c}domain" => $domain,
                ":P{$b->c}social_id" => $id,
            ]);
            $user_id = $b->execute_transact($tn);
            if ($photo_url) {
                try {
                    $this->link_profile_with_web_image($user_id, $photo_url);
                } catch (\Throwable $e) {
                    
                }
            }
            $created = true;
        }
        $user_id ? 0 : \Errors\common_error::R("Ошибка привязки профиля!");
        $user_info = \Auth\UserInfo::F($user_id);
        if ($xcreated !== null) {
            $xcreated = $created;
        }
        return $user_info;
    }

    protected function render_social_success(\Auth\UserInfo $userinfo, bool $created) {
        \smarty\SMW::F()->smarty->assign('user_id', $userinfo->id);
        \smarty\SMW::F()->smarty->assign('created', ($created ? 1 : 0));
        $this->render_view('raw', 'social_success');
    }

    public function actionSocial_fb() {
        \HybridAuth\HYAUTH::F();
        $config = [
            'callback' => sprintf("http%s://%s/Auth/Social_fb", \Router\Request::F()->https ? 's' : '', \Router\Request::F()->host),
            'keys' => [
                'id' => \PresetManager\PresetManager::F()->get_filtered("FACEBOOK_ID", ['Trim', 'NEString', 'DefaultEmptyString']),
                'secret' => \PresetManager\PresetManager::F()->get_filtered("FACEBOOK_SECRET", ['Trim', 'NEString', 'DefaultEmptyString']),
            ],
            'scope' => 'email'
        ];
        $user_profile = null;
        try {
            $face = new \Hybridauth\Provider\Facebook($config);
            $face->authenticate();
            $accessToken = $face->getAccessToken();
            $userProfile = $face->getUserProfile();

            $id = \Helpers\Helpers::NEString($userProfile->identifier, null);
            $email = \Filters\FilterManager::F()->apply_chain($userProfile->email, ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull']);
            $name = \Filters\FilterManager::F()->apply_chain($userProfile->displayName, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $photo_url = \Filters\FilterManager::F()->apply_chain($userProfile->photoURL, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $created = false;
            $user_info = $this->get_user_info_by_social('fb', $id, $email, $name, $photo_url, $created);
            if ($user_info) {
                $this->auth->force_login($user_info->id);
                $this->render_social_success($user_info, $created);
            }
            /**
             * object(Hybridauth\User\Profile)#46 (23) { ["identifier"]=> string(15) "120958282504484" ["webSiteURL"]=> NULL 
             * ["profileURL"]=> string(40) "https://www.facebook.com/120958282504484" 
             * ["photoURL"]=> string(76) "https://graph.facebook.com/v6.0/120958282504484/picture?width=150&height=150" 
             * ["displayName"]=> string(33) "Константин Иванов" ["description"]=> NULL ["firstName"]=> string(20) "Константин" 
             * ["lastName"]=> string(12) "Иванов" ["gender"]=> NULL ["language"]=> NULL ["age"]=> NULL 
             * ["birthDay"]=> int(0) ["birthMonth"]=> int(0) ["birthYear"]=> int(0) 
             * ["email"]=> string(23) "chillvisionru@gmail.com" ["emailVerified"]=> string(23) "chillvisionru@gmail.com" 
             * ["phone"]=> NULL ["address"]=> NULL ["country"]=> NULL ["region"]=> NULL ["city"]=> NULL ["zip"]=> NULL 
             * ["data"]=> array(0) { } }
             */
        } catch (\Exception $e) {
            \smarty\SMW::F()->smarty->assign('error', $e);
            $this->render_view('raw', 'social_error');
            //echo 'Oops, we ran into an issue! ' . $e->getMessage();
            die();
        }
        $this->render_view('raw', 'social_error');
    }

    public function actionSocial_vk() {
        \HybridAuth\HYAUTH::F();
        //'callback'  => Hybridauth\HttpClient\Util::getCurrentUrl(),
        //      'keys'      => ['id' => '', 'secret' => ''],
        $config = [
            'callback' => sprintf("http%s://%s/Auth/Social_vk", \Router\Request::F()->https ? 's' : '', \Router\Request::F()->host),
            'keys' => [
                'id' => \PresetManager\PresetManager::F()->get_filtered("VK_AUTH_ID", ['Trim', 'NEString', 'DefaultEmptyString']),
                'secret' => \PresetManager\PresetManager::F()->get_filtered("VK_AUTH_SECRET", ['Trim', 'NEString', 'DefaultEmptyString']),
            ],
            'scope' => 'email'
        ];
        try {
            $provider = new \Hybridauth\Provider\Vkontakte($config);
            $provider->authenticate();
            $accessToken = $provider->getAccessToken();
            $userProfile = $provider->getUserProfile();
            /*
             * object(Hybridauth\User\Profile)#60 (23) { 
             * ["identifier"]=> int(610312779) 
             * ["webSiteURL"]=> NULL 
             * ["profileURL"]=> string(26) "https://vk.com/id610312779" 
             * ["photoURL"]=> string(0) "" 
             * ["displayName"]=> string(11) "id610312779" 
             * ["description"]=> NULL 
             * ["firstName"]=> string(5) "Chill" 
             * ["lastName"]=> string(6) "Vision" 
             * ["gender"]=> string(6) "female" 
             * ["language"]=> NULL 
             * ["age"]=> NULL 
             * ["birthDay"]=> int(10) 
             * ["birthMonth"]=> int(8) 
             * ["birthYear"]=> int(2000) 
             * ["email"]=> string(22) "chill.vision@yandex.ru" 
             * ["emailVerified"]=> NULL 
             * ["phone"]=> NULL 
             * ["address"]=> NULL 
             * ["country"]=> NULL 
             * ["region"]=> NULL ["city"]=> NULL ["zip"]=> NULL ["data"]=> array(1) { ["education"]=> NULL } }
             */
            $id = \Filters\FilterManager::F()->apply_chain($userProfile->identifier, ['Strip', 'Trim', 'NEString', 'DefaultNull']); // \Helpers\Helpers::NEString($userProfile->identifier, null);
            $email = \Filters\FilterManager::F()->apply_chain($userProfile->email, ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull']);
            $name = \Filters\FilterManager::F()->apply_chain($userProfile->displayName, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            if (preg_match('/^id\d{1,}$/i', $name)) {
                $tname = \Helpers\Helpers::NEString(trim(sprintf("%s %s", $userProfile->firstName, $userProfile->lastName)), null);
                $tname ? $name = $tname : 0;
            }
            $photo_url = \Filters\FilterManager::F()->apply_chain($userProfile->photoURL, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $created = false;
            $user_info = $this->get_user_info_by_social('vk', $id, $email, $name, $photo_url, $created);
            if ($user_info) {
                $this->auth->force_login($user_info->id);
                $this->render_social_success($user_info, $created);
                //$this->render_view('raw', 'social_success');
            }
        } catch (\Throwable $e) {
            \smarty\SMW::F()->smarty->assign('error', $e);
            $this->render_view('raw', 'social_error');
            die();
        }
        $this->render_view('raw', 'social_error');
    }

    public function actionSocial_gu() {
        \HybridAuth\HYAUTH::F();
        //'callback'  => Hybridauth\HttpClient\Util::getCurrentUrl(),
        //      'keys'      => ['id' => '', 'secret' => ''],

        $config = [
            'callback' => sprintf("http%s://%s/Auth/Social_gu", \Router\Request::F()->https ? 's' : '', \Router\Request::F()->host),
            'keys' => ['id' => \PresetManager\PresetManager::F()->get_filtered("GOOGLE_AUTH_ID", ['Trim', 'NEString', 'DefaultEmptyString']),
                'secret' => \PresetManager\PresetManager::F()->get_filtered("GOOGLE_AUTH_SECRET", ['Trim', 'NEString', 'DefaultEmptyString']),
            ],
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile  https://www.googleapis.com/auth/userinfo.email',
            // google's custom auth url params
            'authorize_url_parameters' => [
                'approval_prompt' => 'force', // to pass only when you need to acquire a new refresh token.
            ]
        ];
        try {
            $provider = new \Hybridauth\Provider\Google($config);
            $provider->authenticate();
            $accessToken = $provider->getAccessToken();
            $userProfile = $provider->getUserProfile();
            /*
             * object(Hybridauth\User\Profile)#58 (23) { 
             * ["identifier"]=> string(21) "112048669017838797462" 
             * ["webSiteURL"]=> NULL 
             * ["profileURL"]=> NULL 
             * ["photoURL"]=> string(115) "https://lh6.googleusercontent.com/-PhAtUmpC3vA/AAAAAAAAAAI/AAAAAAAAAAA/AMZuuck0rPjS1L0XDMQSrZN-0cTtkI6dEg/photo.jpg" 
             * ["displayName"]=> string(21) "Иван Чиллов" 
             * ["description"]=> NULL 
             * ["firstName"]=> string(8) "Иван" 
             * ["lastName"]=> string(12) "Чиллов" 
             * ["gender"]=> NULL 
             * ["language"]=> string(2) "ru" 
             * ["age"]=> NULL 
             * ["birthDay"]=> NULL 
             * ["birthMonth"]=> NULL 
             * ["birthYear"]=> NULL 
             * ["email"]=> string(28) "chillvisionruchill@gmail.com" 
             * ["emailVerified"]=> string(28) "chillvisionruchill@gmail.com" 
             * ["phone"]=> NULL ["address"]=> NULL ["country"]=> NULL ["region"]=> NULL ["city"]=> NULL ["zip"]=> NULL ["data"]=> array(0) { } } 
             */
            $id = \Filters\FilterManager::F()->apply_chain($userProfile->identifier, ['Strip', 'Trim', 'NEString', 'DefaultNull']); // \Helpers\Helpers::NEString($userProfile->identifier, null);
            $email = \Filters\FilterManager::F()->apply_chain($userProfile->email, ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull']);
            $name = \Filters\FilterManager::F()->apply_chain($userProfile->displayName, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            if (!$name || preg_match('/^id\d{1,}$/i', $name)) {
                $tname = \Helpers\Helpers::NEString(trim(sprintf("%s %s", $userProfile->firstName, $userProfile->lastName)), null);
                $tname ? $name = $tname : 0;
            }
            $photo_url = \Filters\FilterManager::F()->apply_chain($userProfile->photoURL, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $created = false;
            $user_info = $this->get_user_info_by_social('gu', $id, $email, $name, $photo_url, $created);
            if ($user_info) {
                $this->auth->force_login($user_info->id);
                $this->render_social_success($user_info, $created);
                //$this->render_view('raw', 'social_success');
            }
        } catch (\Throwable $e) {
            \smarty\SMW::F()->smarty->assign('error', $e);
            $this->render_view('raw', 'social_error');
            die();
        }
        $this->render_view('raw', 'social_error');
    }

    public function actionSocial_ok() {
        \HybridAuth\HYAUTH::F();
        $config = [
            'callback' => sprintf("http%s://%s/Auth/Social_ok", \Router\Request::F()->https ? 's' : '', \Router\Request::F()->host),
            'keys' => [
                'id' => \PresetManager\PresetManager::F()->get_filtered("OK_AUTH_ID", ['Trim', 'NEString', 'DefaultEmptyString']),
                'key' => \PresetManager\PresetManager::F()->get_filtered("OK_AUTH_KEY", ['Trim', 'NEString', 'DefaultEmptyString']),
                'secret' => \PresetManager\PresetManager::F()->get_filtered("OK_AUTH_SECRET", ['Trim', 'NEString', 'DefaultEmptyString']),
            ],
        ];
        try {
            $provider = new \Hybridauth\Provider\Odnoklassniki($config);
            $provider->authenticate();
            $accessToken = $provider->getAccessToken();
            $userProfile = $provider->getUserProfile();
            /*
             * object(Hybridauth\User\Profile)#58 (23) { 
             * ["identifier"]=> string(21) "112048669017838797462" 
             * ["webSiteURL"]=> NULL 
             * ["profileURL"]=> NULL 
             * ["photoURL"]=> string(115) "https://lh6.googleusercontent.com/-PhAtUmpC3vA/AAAAAAAAAAI/AAAAAAAAAAA/AMZuuck0rPjS1L0XDMQSrZN-0cTtkI6dEg/photo.jpg" 
             * ["displayName"]=> string(21) "Иван Чиллов" 
             * ["description"]=> NULL 
             * ["firstName"]=> string(8) "Иван" 
             * ["lastName"]=> string(12) "Чиллов" 
             * ["gender"]=> NULL 
             * ["language"]=> string(2) "ru" 
             * ["age"]=> NULL 
             * ["birthDay"]=> NULL 
             * ["birthMonth"]=> NULL 
             * ["birthYear"]=> NULL 
             * ["email"]=> string(28) "chillvisionruchill@gmail.com" 
             * ["emailVerified"]=> string(28) "chillvisionruchill@gmail.com" 
             * ["phone"]=> NULL ["address"]=> NULL ["country"]=> NULL ["region"]=> NULL ["city"]=> NULL ["zip"]=> NULL ["data"]=> array(0) { } } 
             */
            $id = \Filters\FilterManager::F()->apply_chain($userProfile->identifier, ['Strip', 'Trim', 'NEString', 'DefaultNull']); // \Helpers\Helpers::NEString($userProfile->identifier, null);
            $email = \Filters\FilterManager::F()->apply_chain($userProfile->email, ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull']);
            $name = \Filters\FilterManager::F()->apply_chain($userProfile->displayName, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            if (!$name || preg_match('/^id\d{1,}$/i', $name)) {
                $tname = \Helpers\Helpers::NEString(trim(sprintf("%s %s", $userProfile->firstName, $userProfile->lastName)), null);
                $tname ? $name = $tname : 0;
            }
            var_dump($userProfile);
            die();
            $photo_url = \Filters\FilterManager::F()->apply_chain($userProfile->photoURL, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $user_info = $this->get_user_info_by_social('ok', $id, $email, $name, $photo_url);
            if ($user_info) {
                $this->auth->force_login($user_info->id);
                $this->render_view('raw', 'social_success');
            }
        } catch (\Throwable $e) {
            var_dump($e);
            die();
            \smarty\SMW::F()->smarty->assign('error', $e);
            $this->render_view('raw', 'social_error');
            die();
        }
        $this->render_view('raw', 'social_error');
    }

}
