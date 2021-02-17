<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

class CabinetController extends \controllers\FrontEnd\AbstractFrontendController {

    protected function on_after_init() {
        if (!headers_sent()) {
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        }
        return parent::on_after_init();
    }

    public static function get_default_action() {
        return "profile";
    }

    protected function get_role_type() {
        if ($this->auth->is(\Auth\Roles\RoleAdmin::class)) {
            return "client";
        }
        return mb_strtolower($this->auth->user_info->role_type, 'UTF-8');
    }

    public function get_user_ballance_fmt() {
        $id = $this->auth->get_id();
        $b = \Filters\FilterManager::F()->apply_chain(\DB\DB::F()->queryScalar("SELECT money FROM user__wallet WHERE id=:P", [":P" => $id]), ['Float', 'Default0']);
        return number_format($b, 2, '.', '');
    }
    


    protected function actionProfile() {
        \smarty\SMW::F()->smarty->assign('user_info', $this->auth->user_info);
        $payment_success = \DataMap\InputDataMap::F()->exists('payment_success');
        $payment_fail = \DataMap\InputDataMap::F()->exists('payment_fail');
        $order_id = \DataMap\InputDataMap::F()->get_filtered("payment_id", ["IntMore0", 'DefaultNull']);
        \smarty\SMW::F()->smarty->assign("payment_success", $payment_success);
        \smarty\SMW::F()->smarty->assign("payment_fail", $payment_fail);
        \smarty\SMW::F()->smarty->assign("order_id", $order_id);
        if ($order_id && ($payment_fail || $payment_success)) {
            \PayPort\PayportValidator::mk_params()->add("payport_id", $order_id)->run();
        }
        if ($payment_success && $order_id) {
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('chill_default_success_payment'));
        } else if ($payment_fail && $order_id) {
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('chill_default_fail_payment'));
        }

        //\smarty\SMW::F()->smarty->assign('template', "profile_{$this->get_role_type()}");        
        \smarty\SMW::F()->smarty->assign("referal_link", \Referal\ReferalLink::mk_referal_link(\Auth\Auth::F()->get_id()));
        $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('chill_default'));
    }

    //<editor-fold defaultstate="collapsed" desc="api user info">
    protected function API_get_profile() {
        $this->check_access_loc();
        $this->out->add('user_info', $this->auth->user_info);
        $this->out->add('news_subscribed', $this->get_user_is_news($this->auth->id));
        //\ImageFly\MediaContextInfo::register_media_context("avatar", 1200, 1200, 100, 100, true);
    }

    protected function API_update_avatar() { //загружает аватарку
        $this->check_access_loc();
        $this->out->add("log", \ImageFly\ImageFly::F()->handle_upload_manual("avatar", $this->auth->id, md5("avatar"), true), "upload_log");
    }

    protected function API_remove_avatar() {
        $this->check_access_loc();
        \ImageFly\ImageFly::F()->remove_image("avatar", $this->auth->id, md5("avatar"));
    }

    protected function API_post_profile() {
        $profile = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
            'login' => ['Strip', 'Trim', 'NEString', 'EmailMatch',],
            'phone' => ['Strip', 'Trim', 'NEString', 'PhoneMatch', "DefaultNull"],
            'name' => ['Strip', 'Trim', 'NEString',],
            'family' => ['Strip', 'Trim', 'NEString', "DefaultEmptyString"],
            'eldername' => ['Strip', 'Trim', 'NEString', "DefaultEmptyString"],
            "news_subscribed" => ["Boolean", "DefaultTrue"],
            "password" => ['Strip', 'Trim', 'NEString', "DefaultNull"],
        ]);
        \Filters\FilterManager::F()->raise_array_error($profile);
        $current_id = $this->auth->id;
        $login_id = \Auth\UserInfo::S($profile['login']);
        $phone_id = \Auth\UserInfo::PHONE($profile["phone"]);
        if ($login_id && $login_id->valid && $login_id->id !== $current_id) {
            \Errors\common_error::R("login exists");
        }
        if ($phone_id && $phone_id->valid && $phone_id->id !== $current_id) {
            \Errors\common_error::R("phone exists");
        }
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("UPDATE user SET 
                 login=:P{$b->c}login,                
                 news=:P{$b->c}news,
                 phone_strip=:P{$b->c}strip_phone    
                WHERE id=:P{$b->c}id;
                UPDATE user__fields SET
                 phone=:P{$b->c}phone,
                 name=:P{$b->c}name,
                 family=:P{$b->c}family,
                 eldername=:P{$b->c}eldername
                WHERE id=:P{$b->c}id;
                ");
        $b->push_params([
            ":P{$b->c}login" => $profile['login'],
            ":P{$b->c}phone" => $profile["phone"],
            ":P{$b->c}strip_phone" => \Filters\FilterManager::F()->apply_chain($profile["phone"], ["Strip", "Trim", "PhoneMatch", "PhoneClear", "DefaultNull"]),
            ":P{$b->c}name" => $profile["name"],
            ":P{$b->c}family" => $profile["family"],
            ":P{$b->c}eldername" => $profile['eldername'],
            ":P{$b->c}news" => $profile["news_subscribed"] ? 1 : 0,
            ":P{$b->c}id" => $current_id,
        ]);
        $b->inc_counter();
        if ($profile['password']) {
            $enc_pass = \Auth\UserInfo::encrypt_password($profile['password']);
            $b->push("UPDATE user set pass=:P{$b->c}pass WHERE id=:P{$b->c}id;");
            $b->push_params([
                ":P{$b->c}pass" => $enc_pass,
                ":P{$b->c}id" => $current_id
            ]);
        }
        $b->execute_transact();
        $this->auth->force_login($current_id);
        $this->API_get_profile();
    }

    //</editor-fold>



    protected function actionFavorite() {
        $page = $this->GP->get_filtered("p", ["IntMore0", "Default0"]);
        $perpage = 24;
        $offset = $page * $perpage;
        $tn = "a" . md5(__METHOD__);
        $query = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
            CREATE TEMPORARY TABLE `{$tn}` (id INT(11) UNSIGNED, PRIMARY KEY(id));
            INSERT INTO `{$tn}`(id) 
            SELECT product_id FROM user__favorite WHERE user_id=:P ORDER BY created DESC LIMIT {$perpage} OFFSET {$offset};    
        ";
        \DB\DB::F()->exec($query, [":P" => $this->auth->id]);
        \DB\errors\MySQLWarn::F(\DB\DB::F());
        $total = \DB\DB::F()->queryScalari("SELECT COUNT(*) FROM user__favorite WHERE user_id=:P;", [":P" => $this->auth->id]);
        $products = \DataModel\Product\Model\ProductModel::load_join($tn, 0, null, 0);
        \smarty\SMW::F()->smarty->assign('products', $products);
        \smarty\SMW::F()->smarty->assign('paginator', \Helpers\Helpers::mk_paginator($total, $page, $perpage));
        \smarty\SMW::F()->smarty->assign('template', "favorites");
        $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
    }

    protected function on_before_method($requested_action, &$call_method_name) {
        if (!$this->auth->authenticated) {
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('login_cab'));
            die();
        }
        return parent::on_before_method($requested_action, $call_method_name);
    }

    public function actionChill_save() {
        try {
            Helpers\CabinetUserWriter::run();
            \Router\Router::F()->redirect("/Profile", 302);
        } catch (\Throwable $e) {
            \smarty\SMW::F()->smarty->assign('error', $e);
            $this->render_view('front/layout', 'submit_error');
        }
    }

    public function actionmoney_money_money() {
        $pay_url = Helpers\PayportCreateLink::run();
        \Router\Router::F()->redirect($pay_url, 302);
        die();
    }

    protected function API_submit_profile_fd() {
        $this->check_access_loc();
        $form_data = \DataMap\InputDataMap::F();
        if ($form_data) {
            $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
                'login' => ['Strip', 'Trim', 'NEString', 'EmailMatch',],
                'phone' => ['Strip', 'Trim', 'NEString', 'PhoneMatch',],
                'name' => ['Strip', 'Trim', 'NEString',],
                'family' => ['Strip', 'Trim', 'NEString',],
                'eldername' => ['Strip', 'Trim', 'NEString', "DefaultEmptyString"],
                "news" => ["Boolean", "DefaultTrue"],
                "password" => ['Strip', 'Trim', 'NEString', "DefaultNull"],
                "repassword" => ['Strip', 'Trim', 'NEString', "DefaultNull"],
                "about" => ["Strip", "Trim", "NEString", "DefaultEmptyString"],
                "sport" => ["Strip", "Trim", "NEString", "DefaultEmptyString"],
                "hole_id" => ['IntMore0', 'DefaultNull'],
                "time_map" => ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"],
            ]);

            \Filters\FilterManager::F()->raise_array_error($data);
            $current_id = $this->auth->id;
            $login_id = \Auth\UserInfo::S($data['login']);
            if ($login_id && $login_id->valid && $login_id->id !== $current_id) {
                \Errors\common_error::R("login_exists");
            }
            $phone_id = \Auth\UserInfo::PHONE($data['phone']);
            if ($phone_id && $phone_id->valid && $phone_id->id !== $current_id) {
                \Errors\common_error::R("phone_exists");
            }
            $b = \DB\SQLTools\SQLBuilder::F();
            $b->push("UPDATE user SET 
                 login=:P{$b->c}login,                
                 news=:P{$b->c}news
                WHERE id=:P{$b->c}id;
                UPDATE user__fields SET
                 phone=:P{$b->c}phone,
                 name=:P{$b->c}name,
                 family=:P{$b->c}family,
                 eldername=:P{$b->c}eldername
                WHERE id=:P{$b->c}id;
                ");
            $b->push_params([
                ":P{$b->c}login" => $data['login'],
                ":P{$b->c}phone" => $data["phone"],
                ":P{$b->c}name" => $data["name"],
                ":P{$b->c}family" => $data["family"],
                ":P{$b->c}eldername" => $data['eldername'],
                ":P{$b->c}news" => $data["news"] ? 1 : 0,
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
            if ($this->auth_is_trainer()) {
                $b->inc_counter();
                $b->push("INSERT INTO user__comment (id,comment) VALUES(:P{$b->c}u,:P{$b->c}c) ON DUPLICATE KEY UPDATE comment=VALUES(comment);")
                        ->push_params([
                            ":P{$b->c}u" => $current_id,
                            ":P{$b->c}c" => $data["about"],
                ]);
                $b->inc_counter();
                $b->push("INSERT INTO user__sport(id,sport) VALUES(:P{$b->c}id,:P{$b->c}sport) ON DUPLICATE KEY UPDATE sport=VALUES(sport);");
                $b->push_params([
                    ":P{$b->c}id" => $current_id,
                    ":P{$b->c}sport" => $data["sport"],
                ]);
                $b->inc_counter();
                $b->push("INSERT INTO fitness__trainer__hall(trainer_id,hall_id) VALUES(:P{$b->c}i,:P{$b->c}h) ON DUPLICATE KEY UPDATE hall_id=VALUES(hall_id);")
                        ->push_params([
                            ":P{$b->c}i" => $current_id,
                            ":P{$b->c}h" => $data['hole_id'],
                ]);
                $b->inc_counter();
                $items = is_array($data['time_map']) && array_key_exists('items', $data['time_map']) && is_array($data['time_map']['items']) ? $data['time_map']['items'] : [];
                $this->put_rasp_items_into_builder($items, $b, $current_id);
                $b->inc_counter();
            }
            $b->execute_transact();
            $this->auth->force_login($current_id);
            if (count(\DataMap\FileMap::F()->get_by_field_name('ava'))) {
                \ImageFly\ImageFly::F()->process_upload_manual("avatar", $this->auth->id, md5("avatar"), \DataMap\FileMap::F()->get_by_field_name("ava")[0]);
            }
        }
    }

    protected function API_submit_profile() {
        $this->check_access_loc();
        $form_data = $this->GP->get_filtered("data", ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"]);
        if ($form_data) {
            $data = \Filters\FilterManager::F()->apply_filter_array($form_data, [
                'login' => ['Strip', 'Trim', 'NEString', 'EmailMatch',],
                'phone' => ['Strip', 'Trim', 'NEString', 'PhoneMatch',],
                'name' => ['Strip', 'Trim', 'NEString',],
                'family' => ['Strip', 'Trim', 'NEString',],
                'eldername' => ['Strip', 'Trim', 'NEString', "DefaultEmptyString"],
                "news" => ["Boolean", "DefaultTrue"],
                "password" => ['Strip', 'Trim', 'NEString', "DefaultNull"],
                "repassword" => ['Strip', 'Trim', 'NEString', "DefaultNull"],
            ]);
            \Filters\FilterManager::F()->raise_array_error($data);
            $current_id = $this->auth->id;
            $login_id = \Auth\UserInfo::S($data['login']);
            if ($login_id && $login_id->valid && $login_id->id !== $current_id) {
                \Errors\common_error::R("login_exists");
            }
            $b = \DB\SQLTools\SQLBuilder::F();
            $b->push("UPDATE user SET 
                 login=:P{$b->c}login,                
                 news=:P{$b->c}news
                WHERE id=:P{$b->c}id;
                UPDATE user__fields SET
                 phone=:P{$b->c}phone,
                 name=:P{$b->c}name,
                 family=:P{$b->c}family,
                 eldername=:P{$b->c}eldername
                WHERE id=:P{$b->c}id;
                ");
            $b->push_params([
                ":P{$b->c}login" => $data['login'],
                ":P{$b->c}phone" => $data["phone"],
                ":P{$b->c}name" => $data["name"],
                ":P{$b->c}family" => $data["family"],
                ":P{$b->c}eldername" => $data['eldername'],
                ":P{$b->c}news" => $data["news"] ? 1 : 0,
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
            \GEM\GEM::F()->run(\GEM\GEM::ON_USER_DATA_CHANGED, \GEM\EventKVS::F(["user_id" => $current_id]));
            $this->auth->force_login($current_id);
        }
    }

    protected function API_submit_profile_fd_hole() {
        $this->check_access_loc();
        $form_data = \DataMap\InputDataMap::F();
        if ($form_data) {
            $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
                'login' => ['Strip', 'Trim', 'NEString', 'EmailMatch',],
                'phone' => ['Strip', 'Trim', 'NEString', 'PhoneMatch',],
                'name' => ['Strip', 'Trim', 'NEString',],
                'family' => ['Strip', 'Trim', 'NEString',],
                'eldername' => ['Strip', 'Trim', 'NEString', "DefaultEmptyString"],
                "news" => ["Boolean", "DefaultTrue"],
                "password" => ['Strip', 'Trim', 'NEString', "DefaultNull"],
                "repassword" => ['Strip', 'Trim', 'NEString', "DefaultNull"],
                "about" => ["Strip", "Trim", "NEString", "DefaultEmptyString"],
                "sport" => ["Strip", "Trim", "NEString", "DefaultEmptyString"],
                "owned_object" => ['IntMore0', 'DefaultNull'],
                "holelat" => ["Float"],
                "holelon" => ["Float"],
                "holename" => ["Strip", 'Trim', 'NEString'],
                "holephone" => ['Strip', 'Trim', 'NEString', 'PhoneMatch',],
                "holeaddress" => ["Strip", 'Trim', 'NEString'],
            ]);

            \Filters\FilterManager::F()->raise_array_error($data);
            $current_id = $this->auth->id;
            $login_id = \Auth\UserInfo::S($data['login']);
            if ($login_id && $login_id->valid && $login_id->id !== $current_id) {
                \Errors\common_error::R("login_exists");
            }
            $phone_id = \Auth\UserInfo::PHONE($data['phone']);
            if ($phone_id && $phone_id->valid && $phone_id->id !== $current_id) {
                \Errors\common_error::R("phone_exists");
            }
            $b = \DB\SQLTools\SQLBuilder::F();
            $b->push("UPDATE user SET 
                 login=:P{$b->c}login,                
                 news=:P{$b->c}news
                WHERE id=:P{$b->c}id;
                UPDATE user__fields SET
                 phone=:P{$b->c}phone,
                 name=:P{$b->c}name,
                 family=:P{$b->c}family,
                 eldername=:P{$b->c}eldername
                WHERE id=:P{$b->c}id;
                ");
            $b->push_params([
                ":P{$b->c}login" => $data['login'],
                ":P{$b->c}phone" => $data["phone"],
                ":P{$b->c}name" => $data["name"],
                ":P{$b->c}family" => $data["family"],
                ":P{$b->c}eldername" => $data['eldername'],
                ":P{$b->c}news" => $data["news"] ? 1 : 0,
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
            if ($this->auth_is_hole()) {
                $b->inc_counter();
                if ($data["owned_object"]) {
                    $b->push("SET @VAR_HOLE_TMP=:P{$b->c}oo;");
                    $b->push_param(":P{$b->c}oo", $data['owned_object']);
                    $b->push("UPDATE fitness__places A JOIN fitness__place__owner B ON(A.id=B.place_id)
                        SET name=:P{$b->c}name,address=:P{$b->c}addr,lat=:P{$b->c}lat,lon=:P{$b->c}lon,phone=:P{$b->c}phone
                        WHERE B.user_id=:P{$b->c}user AND A.id=@VAR_HOLE_TMP;");
                } else {
                    $b->push("INSERT INTO fitness__places (name,address,lat,lon,phone,features)
                        VALUES(:P{$b->c}name,:P{$b->c}addr,:P{$b->c}lat,:P{$b->c}lon,:P{$b->c}phone,'');
                        INSERT INTO fitness__place__owner(place_id,user_id) VALUES(LAST_INSERT_ID(),:P{$b->c}user);    
                        ");
                }
                $b->push_params([
                    ":P{$b->c}name" => $data["holename"],
                    ":P{$b->c}addr" => $data["holeaddress"],
                    ":P{$b->c}phone" => $data["holephone"],
                    ":P{$b->c}lat" => $data["holelat"],
                    ":P{$b->c}lon" => $data["holelon"],
                ])->push_param(":P{$b->c}user", $current_id);
            }
            $b->execute_transact();
            $this->auth->force_login($current_id);
            if (count(\DataMap\FileMap::F()->get_by_field_name('ava'))) {
                \ImageFly\ImageFly::F()->process_upload_manual("avatar", $this->auth->id, md5("avatar"), \DataMap\FileMap::F()->get_by_field_name("ava")[0]);
            }
        }
    }

    protected function check_access_loc() {
        // $this->out->add("ccs", \Auth\Roles\RoleClient::is($this->auth->role)?"yws":"no");
        if (!$this->auth->is_authentificated()) {
            \Auth\AuthError::R(\Auth\AuthError::NOT_AUTHORIZED);
        }
    }

    protected function API_acl() {
        $this->check_access_loc();
        $acl = \Auth\ProductAccessManager::C(\Auth\Auth::F()->get_id());
        $this->out->add("acl", $acl->items);
    }

    public function auth_is_client() {
        return $this->auth->is(\Auth\Roles\RoleClient::class) && (!$this->auth->is(\Auth\Roles\RoleTrainer::class));
    }

    public function auth_is_trainer() {
        return $this->auth->is(\Auth\Roles\RoleTrainer::class);
    }

    public function auth_is_hole() {
        return $this->auth->is(\Auth\Roles\RoleHole::class);
    }

    public function actionTrainerCalendar() {
        if (\Auth\Auth::F()->is(\Auth\Roles\RoleTrainer::class)) {
            \smarty\SMW::F()->smarty->assign('user_info', $this->auth->user_info);
            \smarty\SMW::F()->smarty->assign('template', "calendar");
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
        } else {
            \smarty\SMW::F()->smarty->assign('template', "only_for_trainer");
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
        }
    }

    public function actionTrainerPlaces() {
        if (\Auth\Auth::F()->is(\Auth\Roles\RoleTrainer::class)) {
            \smarty\SMW::F()->smarty->assign('user_info', $this->auth->user_info);
            \smarty\SMW::F()->smarty->assign('template', "trainer_places");
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
        } else {
            \smarty\SMW::F()->smarty->assign('template', "only_for_trainer");
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
        }
    }

    protected function API_trainer_calendar() {
        $calendar_data = \DB\DB::F()->queryAll("SELECT * FROM fitness__trainer__interval WHERE trainer_id=:P", [":P" => $this->auth->id]);
        $this->out->add('items', $calendar_data);
        $this->out->add("x", "y");
    }

    public function get_trainer_calendar() {
        $calendar_data = \DB\DB::F()->queryAll("SELECT * FROM fitness__trainer__interval WHERE trainer_id=:P", [":P" => $this->auth->id]);
        return ['items' => $calendar_data, 'status' => 'ok'];
    }

    protected function put_rasp_items_into_builder(array $items, \DB\SQLTools\SQLBuilder $builder, int $user_id) {
        $citems = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                try {
                    $citem = \Filters\FilterManager::F()->apply_filter_array($item, [
                        'start' => ["AnyInt",],
                        "length" => ["IntMore0"]
                            ], \Filters\params\ArrayParamBuilder::B([
                                'start' => [
                                    'AnyInt' => [
                                        'min' => 0
                                    ]
                                ]
                    ]));
                    \Filters\FilterManager::F()->raise_array_error($citem);
                    $citems[] = $citem;
                } catch (\Throwable $e) {
                    continue;
                }
            }
        }
        $intervals = [];
        $witems = [];
        foreach ($citems as $item) {
            $item['end'] = $item['start'] + $item['length'];
            foreach ($intervals as $interval) {
                if (($item['start'] >= $interval['start'] && $item['start'] < $interval['end']) || //start inside interval
                        ($item['end'] >= $interval['start'] && $item['end'] <= $interval['end']) || // end inside interval
                        ($interval['start'] >= $item['start'] && $interval['start'] < $item['end']) || // interval start inside current
                        ($interval['end'] >= $item['start'] && $interval['end'] <= $item['end'])) {
                    \Errors\common_error::R("interval intersect");
                }
            }
            $intervals[] = ['start' => $item['start'], 'end' => $item['end']];
            $witems[] = ['start' => $item['start'], 'length' => $item['length']];
        }
        $builder->push("DELETE FROM fitness__trainer__interval WHERE trainer_id=:Puser;")
                ->push_param(":Puser", $user_id);
        $p = [];
        $i = [];
        $c = 0;
        foreach ($witems as $item) {
            $i[] = "(:Puser,:P{$c}time,:P{$c}len,1)";
            $p[":P{$c}time"] = $item['start'];
            $p[":P{$c}len"] = $item['length'];
            $c++;
        }
        if (count($i)) {
            $builder->push(sprintf("INSERT INTO fitness__trainer__interval (trainer_id,time_id,length,active)
                VALUES %s ON DUPLICATE KEY UPDATE length=VALUES(length);", implode(",", $i)))
                    ->push_params($p);
        }
    }

    protected function API_post_trainer_calendar() {
        $data = $this->GP->get_filtered("data", ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"]);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $md = \DataMap\CommonDataMap::F()->rebind($data);
        $items = $md->get_filtered("items", ["NEArray", "DefaultEmptyArray"]);
        $builder = \DB\SQLTools\SQLBuilder::F();
        $this->put_rasp_items_into_builder($items, $builder, $this->auth->id);
        $builder->execute_transact();
        $this->API_trainer_calendar();
    }

    public function load_trainer_about($escape = false) {
        $id = $this->auth->id;
        $q = \Filters\FilterManager::F()->apply_chain(\DB\DB::F()->queryScalar("SELECT comment FROM user__comment WHERE id=:P", [":P" => $id]), ["Strip", "Trim", "NEString", "DefaultNull"]);
        return $escape ? htmlentities($q, ENT_QUOTES) : $q;
    }

    public function load_trainer_sport($escape = false) {
        $id = $this->auth->id;
        $q = \Filters\FilterManager::F()->apply_chain(\DB\DB::F()->queryScalar("SELECT sport FROM user__sport WHERE id=:P", [":P" => $id]), ["Strip", "Trim", "NEString", "DefaultNull"]);
        return $escape ? htmlentities($q, ENT_QUOTES) : $q;
    }

    public function load_trainer_hole_info() {
        $id = $this->auth->id;
        $q = "SELECT B.id,B.name,B.address,B.lat,B.lon,B.default_image,B.phone  FROM fitness__trainer__hall A JOIN fitness__places B ON(A.hall_id=B.id) WHERE A.trainer_id=:P;";
        $row = \DB\DB::F()->queryRow($q, [":P" => $id]);
        return $row && is_array($row) ? $row : null;
    }

    public function load_current_hole_info() {
        $id = $this->auth->id;
        $q = "SELECT A.id,A.name,A.address,A.lat,A.lon,A.default_image,A.phone  FROM fitness__places A JOIN fitness__place__owner B ON(A.id=B.place_id) WHERE B.user_id=:P;";
        $row = \DB\DB::F()->queryRow($q, [":P" => $id]);
        return $row && is_array($row) ? $row : null;
    }

    protected function API_trainer_places() {
        $this->out->add('items', $this->get_trainer_holes());
    }

    public function get_trainer_holes() {
        $id = $this->auth->id;
        $q = "SELECT B.id,B.name,B.address,B.default_image,B.lat,B.lon
            FROM fitness__trainer__hall A JOIN fitness__places B ON(A.hall_id=B.id) WHERE A.trainer_id=:P ORDER BY B.id";
        return \DB\DB::F()->queryAll($q, [":P" => $id]);
    }

    public function API_trainer_api_get_point_list() {
        \Content\TrainingHall\TrainigHallMapLister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_trainer_points_selected() {
        $id = $this->auth->id;
        $points = \DataMap\InputDataMap::F()->get_filtered('points', ["NEArray", 'ArrayOfInt', 'NEArray', 'DefaultEmptyArray'], \Filters\params\ArrayParamBuilder::B(['ArrayOfInt' => ['min' => 1]], true)->get_param_set_for_property());
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("DELETE FROM fitness__trainer__hall WHERE trainer_id=:P;")
                ->push_param(":P", $id);
        $i = [];
        $p = [];
        $c = 0;
        foreach ($points as $point_id) {
            $i[] = "(:P,:P{$c}i)";
            $p[":P{$c}i"] = $point_id;
            $c++;
        }
        if (count($i)) {
            $b->push(sprintf("INSERT INTO fitness__trainer__hall(trainer_id,hall_id) VALUES %s ON DUPLICATE KEY UPDATE trainer_id=VALUES(trainer_id);", implode(",", $i)))
                    ->push_params($p);
        }
        $b->execute_transact();
        $this->API_trainer_places();
    }

    protected function API_remove_trainer_place() {
        $id = $this->auth->id;
        $point_id = \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0']);
        \Filters\FilterManager::F()->raise_array_error(['id' => $point_id]);
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM fitness__trainer__hall WHERE trainer_id=:P AND hall_id=:PP")
                ->push_param(":P", $id)
                ->push_param(":PP", $point_id)->execute_transact();
        $this->API_trainer_places();
    }

    /**
     * 
     * @return \Content\ClientPackage\ClientPackage
     */
    public function get_client_package() {
        $id = $this->auth->id;
        return \Content\ClientPackage\ClientPackage::F($id);
    }

    public function actionCreate_order() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0", "DefaultNull"]);
        $id ? 0 : \Router\NotFoundError::R("not found");
        try {
            try {
                $package = \Content\ClientPackage\Package::C($id);
            } catch (\Throwable $e) {
                \Errors\common_error::R("Пакет не найден");
            }
            $package->active ? 0 : \Errors\common_error::R("Этот пакет не доступен для заказа");
            $user_id = $this->auth->get_id();
            $b = \DB\SQLTools\SQLBuilder::F();
            $tv = "@a" . md5(__METHOD__);
            $rid_var = $b->push("INSERT INTO fitness__user__order (user_id,package_id,package_name,cost,usages,datum,expires)
                VALUES(:Puser,:Ppackage,:Pname,:Pcost,:Pusages,NOW(),DATE_ADD(NOW(), INTERVAL :Pdays day));
                SET {$tv}=LAST_INSERT_ID();
                ")->push_params([
                        ":Puser" => $user_id,
                        ":Ppackage" => $package->id,
                        ":Pname" => $package->name,
                        ":Pcost" => $package->price,
                        ":Pusages" => $package->usages,
                        ":Pdays" => $package->days,
                    ])->execute_transact($tv);
            $order = \DB\DB::F()->queryRow("SELECT * FROM fitness__user__order WHERE id=:P", [":P" => $rid_var]);
            \smarty\SMW::F()->smarty->assign('user_info', $this->auth->user_info);
            \smarty\SMW::F()->smarty->assign('template', "order_success");
            \smarty\SMW::F()->smarty->assign('package', $package);
            \smarty\SMW::F()->smarty->assign('order', $order);
            \Router\Router::F()->redirect("/Cabinet/profile");
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default_empty'));
        } catch (\Throwable $e) {
            \smarty\SMW::F()->smarty->assign('user_info', $this->auth->user_info);
            \smarty\SMW::F()->smarty->assign('template', "order_error");
            \smarty\SMW::F()->smarty->assign('error', $e);
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default_empty'));
        }
    }

    protected function API_create_request() {
        //списывать очко!!!!!?? триггером? и вертать триггером
        $p = \DataMap\InputDataMap::F()->get_filtered('p', ['IntMore0']);
        $t = \DataMap\InputDataMap::F()->get_filtered('t', ['IntMore0']);
        $d = \DataMap\InputDataMap::F()->get_filtered('d', ['DateMatch']);
        $tm = \DataMap\InputDataMap::F()->get_filtered('tm', ['AnyInt'], \Filters\params\ArrayParamBuilder::B(['AnyInt' => ['min' => 0]], true)->get_param_set_for_property());
        \Filters\FilterManager::F()->raise_array_error(compact('p', 't', 'd', 'tm'));
        $this->auth_is_client() ? 0 : \Errors\common_error::R("invalid account type");
        //проверить существование точки, тренера, работы тренера на точке, точки в расписанииу тренера
        //проверить незанятость времени на указанную дату
        // и нужен мутекс - вероятно по тренеру - на все
        /* @var $d \DateTime */
        $d->setTime(0, 0, 0, 0);
        $dow = intval($d->format('N')) - 1;
        $calculated_dow = intdiv($tm, 86400);
        $dow === $calculated_dow ? 0 : \Errors\common_error::R("selected date does not matches selected interval");
        $mutex = \Mutex\SimpleNamedMutex::F("TRAINING"); //one for all
        try {
            $mutex->get();
            $row = \DB\DB::F()->queryRow("SELECT A.trainer_id t,A.hall_id p,B.time_id tm,B.length ln
                FROM fitness__trainer__hall A LEFT JOIN fitness__trainer__interval B ON(A.trainer_id=B.trainer_id)
                WHERE A.trainer_id=:P AND A.hall_id=:PP AND B.time_id=:PPP
                ", [":P" => $t, ":PP" => $p, ":PPP" => $tm]);
            $row ? 0 : \Errors\common_error::R("trainer does not work in selected place");
            $row['tm'] !== null ? 0 : \Errors\common_error::R("selected trainer does not work in seected time");
            $length = intval($row['ln']);
            // дополнительно - проверить что выбранная дата - тот же день недели что и таймоффсет
            $row = \DB\DB::F()->queryRow("SELECT * FROM fitness__trainer__buisy WHERE trainer_id=:P AND datum=:PP AND start=:PPP AND state!=2", [
                ":P" => $t, ":PP" => $d->format('Y-m-d'), ":PPP" => $tm
            ]);
            $row ? \Errors\common_error::R("selected time is buisy") : 0;
            $b = \DB\SQLTools\SQLBuilder::F();
            $tvar = "@a" . md5(__METHOD__);
            // Убеждаемся что у юзера есть свободное очко!!
            $usages = \DB\DB::F()->queryScalari("SELECT usage_count FROM user__usages WHERE user_id=:PP", [":PP" => $this->auth->get_id()]);
            $usages > 0 ? 0 : \Errors\common_error::R("Чтобы записываться на тренировки Вам нужен пакет занятий.");
            $rid_id = $b->push("INSERT INTO fitness__trainer__buisy (trainer_id,user_id,place_id,place_name,trainer_name,client_name,datum,start,end,state)
                VALUES( :Pt,:Pu,:Pp,(SELECT name FROM fitness__places WHERE id=:Pp),
                (SELECT TRIM(CONCAT(COALESCE(family,''),' ',COALESCE(name,''),' ',COALESCE(eldername,''))) FROM user U1 JOIN user__fields UF1 ON(UF1.id=U1.id) WHERE U1.id=:Pt),
                (SELECT TRIM(CONCAT(COALESCE(family,''),' ',COALESCE(name,''),' ',COALESCE(eldername,''))) FROM user U2 JOIN user__fields UF2 ON(UF2.id=U2.id) WHERE U2.id=:Pu),
                :Pdate,:Pstart,:Pend,0);                
                SET {$tvar}=LAST_INSERT_ID();
                UPDATE user__usages SET usage_count=usage_count-1 WHERE user_id=:Pu;    
                ")->push_params([
                        ":Pt" => $t, ":Pu" => $this->auth->get_id(), ":Pp" => $p, ":Pdate" => $d->format('Y-m-d'), ":Pstart" => $tm, ":Pend" => $tm + $length,
                    ])->execute_transact($tvar);
            //вычитаем очко из пользака - обратно его вернет триггер при отмене
            $this->API_get_training_info($rid_id);
            $this->create_train_notification($rid_id);
        } finally {
            $mutex->release();
        }
    }

    protected function create_train_notification(int $rid_id) {
        \Content\Training\Notification::mk_params()->add("id", $rid_id)->run();
    }

    protected function API_get_training_info(int $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $row = \DB\DB::F()->queryRow("SELECT * FROM fitness__trainer__buisy WHERE id=:Pid", [":Pid" => $id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->out->add('training', $row);
    }

    public function actionMyTraining() {

        try {
            $id = \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
            $id ? 0 : \Errors\common_error::R("no id");
            $training = \Content\Training\Training::F($id);

            $training && $training->valid && $training->user_id === $this->auth->id ? 0 : \Errors\common_error::R("unfoundable");
            \smarty\SMW::F()->smarty->assign('user_info', $this->auth->user_info);
            \smarty\SMW::F()->smarty->assign('training', $training);
            \smarty\SMW::F()->smarty->assign('template', "training");
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
        } catch (\Throwable $e) {
            throw $e;
            throw \Router\NotFoundError::R("not found");
        }
    }

    public function actionMyHistory() {
        $page = \DataMap\InputDataMap::F()->get_filtered('page', ['IntMore0', 'Default0']);
        $perpage = 20;
        $input = \DataMap\CommonDataMap::F();
        $input->set("page", $page)->set("perpage", $perpage);
        $lister = \Content\Training\MyHistoryLister::F($input);
        $result = $lister->run();

        $paginator = $this->create_paginator($result->total, $result->page, $result->perpage);
        \smarty\SMW::F()->smarty->assign('user_info', $this->auth->user_info);
        \smarty\SMW::F()->smarty->assign('items', $result->items);
        \smarty\SMW::F()->smarty->assign('paginator', $paginator);

        \smarty\SMW::F()->smarty->assign('template', "trainig_list_history");
        $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
    }

    public function actionMyPlan() {
        $page = \DataMap\InputDataMap::F()->get_filtered('page', ['IntMore0', 'Default0']);
        $perpage = 20;
        $input = \DataMap\CommonDataMap::F();
        $input->set("page", $page)->set("perpage", $perpage);
        $lister = \Content\Training\MyPlanLister::F($input);
        $result = $lister->run();

        $paginator = $this->create_paginator($result->total, $result->page, $result->perpage);
        \smarty\SMW::F()->smarty->assign('user_info', $this->auth->user_info);
        \smarty\SMW::F()->smarty->assign('items', $result->items);
        \smarty\SMW::F()->smarty->assign('paginator', $paginator);
        \smarty\SMW::F()->smarty->assign('template', "trainig_list_plan");
        $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
    }

    public function actionTrainerPlan() {
        $page = \DataMap\InputDataMap::F()->get_filtered('page', ['IntMore0', 'Default0']);
        $perpage = 20;
        $input = \DataMap\CommonDataMap::F();
        $input->set("page", $page)->set("perpage", $perpage);
        $lister = \Content\Training\TrainerPlanLister::F($input);
        $result = $lister->run();
        $paginator = $this->create_paginator($result->total, $result->page, $result->perpage);
        \smarty\SMW::F()->smarty->assign('items', $result->items);
        \smarty\SMW::F()->smarty->assign('paginator', $paginator);
        \smarty\SMW::F()->smarty->assign('template', "trainer_plan");
        $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
    }

    public function actionTrainerHistory() {
        $page = \DataMap\InputDataMap::F()->get_filtered('page', ['IntMore0', 'Default0']);
        $perpage = 20;
        $input = \DataMap\CommonDataMap::F();
        $input->set("page", $page)->set("perpage", $perpage);
        $lister = \Content\Training\TrainerHistoryLister::F($input);
        $result = $lister->run();
        $paginator = $this->create_paginator($result->total, $result->page, $result->perpage);
        \smarty\SMW::F()->smarty->assign('items', $result->items);
        \smarty\SMW::F()->smarty->assign('paginator', $paginator);
        \smarty\SMW::F()->smarty->assign('template', "trainer_history");
        $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
    }

    public function actionTrainerTraining() {

        try {
            $id = \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
            $id ? 0 : \Errors\common_error::R("no id");
            $training = \Content\Training\Training::F($id);

            $training && $training->valid && $training->trainer_id === $this->auth->id ? 0 : \Errors\common_error::R("unfoundable");
            \smarty\SMW::F()->smarty->assign('training', $training);
            \smarty\SMW::F()->smarty->assign('template', "training_trainer");
            $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
        } catch (\Throwable $e) {
            throw $e;
            throw \Router\NotFoundError::R("not found");
        }
    }

    public function get_owned_object() {
        $user_id = $this->auth->get_id();
        $query = "SELECT  id,name,address,phone,lat,lon,default_image  FROM fitness__place__owner A JOIN fitness__places B ON(A.place_id=B.id) WHERE A.user_id=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => $user_id]);
        return $row ? $row : null;
    }

    protected function API_get_events_client() {
        $client_id = $this->auth->get_id();
        $datum = \DataMap\InputDataMap::F()->get_filtered("date", ['DateMatch', 'DefaultNull']);
        $datum ? 0 : \Errors\common_error::R("invalid date");
        /* @var $datum \DateTime */
        $datum->setTime(0, 0, 0, 0);
        $query = "            
SELECT
A.id,DATE_FORMAT(A.datum,'%d.%m.%Y') `date`, TRIM(CONCAT( COALESCE(UF.family,''),' ',COALESCE(UF.name,'') )) client,
UF.phone client_phone,
TRIM(CONCAT( COALESCE(TF.family,''),' ',COALESCE(TF.name,'') )) trainer,
TF.phone trainer_phone,
P.name hole,P.address hole_address,S.sport,
DATE_FORMAT(DATE_ADD(datum, INTERVAL MOD(start,86400) SECOND),'%H:%i') `time`,
CASE state WHEN 1 THEN 'Подтверждено' WHEN 2 THEN 'Отменено' ELSE 'Не подтверждено' END state


FROM fitness__trainer__buisy A
JOIN user U ON(U.id=A.user_id) JOIN user__fields UF ON(UF.id=U.id)
JOIN user T ON(T.id=A.trainer_id) JOIN user__fields TF ON(TF.id=T.id)
JOIN user__sport S ON(S.id=T.id)
JOIN fitness__places P ON(P.id=A.place_id)
WHERE A.user_id=:P AND datum=:PD
            ";
        $this->out->add('events', \DB\DB::F()->queryAll($query, [":P" => $client_id, ":PD" => $datum->format('Y-m-d')]));
    }

    protected function API_get_events_trainer() {
        $client_id = $this->auth->get_id();
        $datum = \DataMap\InputDataMap::F()->get_filtered("date", ['DateMatch', 'DefaultNull']);
        $datum ? 0 : \Errors\common_error::R("invalid date");
        /* @var $datum \DateTime */
        $datum->setTime(0, 0, 0, 0);
        $query = "            
SELECT
A.id,DATE_FORMAT(A.datum,'%d.%m.%Y') `date`, TRIM(CONCAT( COALESCE(UF.family,''),' ',COALESCE(UF.name,'') )) client,
UF.phone client_phone,
TRIM(CONCAT( COALESCE(TF.family,''),' ',COALESCE(TF.name,'') )) trainer,
TF.phone trainer_phone,
P.name hole,P.address hole_address,S.sport,
DATE_FORMAT(DATE_ADD(datum, INTERVAL MOD(start,86400) SECOND),'%H:%i') `time`,
CASE state WHEN 1 THEN 'Подтверждено' WHEN 2 THEN 'Отменено' ELSE 'Не подтверждено' END state


FROM fitness__trainer__buisy A
JOIN user U ON(U.id=A.user_id) JOIN user__fields UF ON(UF.id=U.id)
JOIN user T ON(T.id=A.trainer_id) JOIN user__fields TF ON(TF.id=T.id)
JOIN user__sport S ON(S.id=T.id)
JOIN fitness__places P ON(P.id=A.place_id)
WHERE A.trainer_id=:P AND datum=:PD
            ";
        $this->out->add('events', \DB\DB::F()->queryAll($query, [":P" => $client_id, ":PD" => $datum->format('Y-m-d')]));
    }

    protected function API_get_events_hole() {
        $client_id = $this->auth->get_id();
        $hole = $this->load_current_hole_info();
        $hole_id = $hole ? intval($hole['id']) : null;
        $datum = \DataMap\InputDataMap::F()->get_filtered("date", ['DateMatch', 'DefaultNull']);
        $datum ? 0 : \Errors\common_error::R("invalid date");
        $hole_id ? 0 : \Errors\common_error::R("invalid account state");
        /* @var $datum \DateTime */
        $datum->setTime(0, 0, 0, 0);
        $query = "            
SELECT
A.id,DATE_FORMAT(A.datum,'%d.%m.%Y') `date`, TRIM(CONCAT( COALESCE(UF.family,''),' ',COALESCE(UF.name,'') )) client,
UF.phone client_phone,
TRIM(CONCAT( COALESCE(TF.family,''),' ',COALESCE(TF.name,'') )) trainer,
TF.phone trainer_phone,
P.name hole,P.address hole_address,S.sport,
DATE_FORMAT(DATE_ADD(datum, INTERVAL MOD(start,86400) SECOND),'%H:%i') `time`,
CASE state WHEN 1 THEN 'Подтверждено' WHEN 2 THEN 'Отменено' ELSE 'Не подтверждено' END state


FROM fitness__trainer__buisy A
JOIN user U ON(U.id=A.user_id) JOIN user__fields UF ON(UF.id=U.id)
JOIN user T ON(T.id=A.trainer_id) JOIN user__fields TF ON(TF.id=T.id)
JOIN user__sport S ON(S.id=T.id)
JOIN fitness__places P ON(P.id=A.place_id)
WHERE A.place_id=:P AND datum=:PD
            ";
        $this->out->add('events', \DB\DB::F()->queryAll($query, [":P" => $hole_id, ":PD" => $datum->format('Y-m-d')]));
    }

    protected function API_apply_promo() {
        $this->auth->is_authentificated() ? 0 : \Errors\common_error::R("auth_rqrd");
        $value = \DataMap\InputDataMap::F()->get_filtered('value', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $value ? 0 : \Errors\common_error::R('not_found');
        $promo = \Promo\Promo::F(null, $value);
        $token = \DataMap\InputDataMap::F()->get_filtered('token', ['Strip','Trim','NEString','DefaultEmptyString']);
        $this->check_csrf_throw($token, 'promo',false);
        $used = \DB\DB::F()->queryRow("select * from chill__promo__user WHERE promo_id=:P AND user_id=:PP", [":P" => $promo->id, ":PP" => $this->auth->get_id()]);
        $used ? \Errors\common_error::R("alredy_used") : 0;
        \DB\SQLTools\SQLBuilder::F()->push("
            INSERT INTO  user__wallet (id,money) VALUES(:Puser,0) ON DUPLICATE KEY UPDATE money=money+VALUES(money);
            UPDATE user__wallet A SET money=money+:Pval WHERE id=:Puser;
            INSERT INTO chill__promo__user (promo_id,user_id,activated) VALUES(:Ppromo,:Puser,NOW());
            ")->push_params([
            ":Puser" => $this->auth->get_id(),
            ":Pval" => $promo->value,
            ":Ppromo" => $promo->id,
        ])->execute_transact();
        $this->out->add('money', $this->get_user_ballance_fmt());                
    }

    public function actionTestFn() {
        $this->render_view('front/layout', 'chill_fn_test');
    }

    public function actionTestMenu() {

        $this->render_view('front/layout', 'chill_test_menu');
        die();
    }

}
