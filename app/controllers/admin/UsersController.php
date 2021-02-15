<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class UsersController extends \controllers\admin\AbstractAdminController {

    protected function actionIndex() {
        $this->render_view("admin", "list");
    }

    protected function on_after_init() {
        return parent::on_after_init();
    }

    protected function API_get_data() {
        $limitation = \ADVTable\Limit\FixedTokenLimit::F();
        $condition = \ADVTable\Filter\FixedTokenFilter::F(null, [
                    'id' => 'Int:A.id',
                    'name' => 'String:C.search_name',
                    'login' => 'String:A.login',
                    'locked' => 'Int:A.locked',
                    'approved' => 'Int:A.is_approved',
                    'phone' => 'Phone:C.search_phone',
                    'created' => 'Date:A.created',
                    'birth_date' => 'Date:A.birth_date',
                    'money' => 'Int:X.money',
                    'news' => 'Int:A.news',
        ]);

        $direction = \ADVTable\Sort\FixedTokenSort::F(NULL, [
                    'id' => 'A.id',
                    'name' => 'C.search_name|A.id',
                    'login' => 'A.login|A.id',
                    'locked' => 'A.locked|A.id',
                    'approved' => 'A.is_approved|A.id',
                    'phone' => 'C.search_phone|A.id',
                    'created' => 'A.created|A.id',
                    'birth_date' => 'A.birth_date|A.id',
                    'money' => 'X.money|A.id',
                    'news' => 'A.news|A.id',
        ]);
        $direction->tokens_separator = "|";
        $p = [];
        $c = 0;
        $where = $condition->buildSQL($p, $c);
        $query = "SELECT SQL_CALC_FOUND_ROWS 
                A.id,A.login,A.role,
                A.locked locked,
                A.is_approved approved,A.news,
                DATE_FORMAT(A.created,'%%d.%%m.%%Y') created,
                TRIM( CONCAT( COALESCE(B.family,''),' ',COALESCE(B.name,''),' ',COALESCE(B.eldername,'')   ) ) name,
                B.phone,X.money,DATE_FORMAT(A.birth_date,'%%d.%%m.%%Y')birth_date
          FROM 
          user A LEFT JOIN user__fields B ON(A.id=B.id) 
          LEFT JOIN user__wallet X ON(X.id=A.id)
          LEFT JOIN user__search C ON(C.id=A.id) 
          
          %s %s %s %s;";
        $rows = \DB\DB::F()->queryAll(sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit), $p);
        if (!count($rows) && $limitation->page) {
            $limitation->setPage(0);
            $rows = \DB\DB::F()->queryAll(sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit), $p);
        }
        $total = \DB\DB::F()->get_found_rows();
        $this->out->add('total', $total)->add('items', $rows)->add('page', $limitation->page)->add('perpage', $limitation->perpage);
    }

    protected function API_get_user(int $user_id = null) {
        $id = $user_id ? $user_id : \DataMap\GPDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? FALSE : \Errors\common_error::R("invalid request");
        $query = "SELECT A.*,B.name,B.family,B.eldername,B.phone,C.`comment` 
            ,DATE_FORMAT(A.created,'%d.%m.%Y') created,A.news,
            DATE_FORMAT(A.birth_date,'%d.%m.%Y') birth_date,
            COALESCE(X.money,0) money
            FROM 
            user A LEFT JOIN user__fields B ON(A.id=B.id)
            LEFT JOIN user__comment C ON(C.id=A.id)
            LEFT JOIN user__wallet X ON(X.id=A.id)
            WHERE A.id=:P
            ";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $row ? FALSE : \Errors\common_error::R("not found");
        $this->out->add('data', $row);
    }

    protected function API_post_user() {
        $x = \DataMap\GPDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'DefaultNull']);
        $x && is_array($x) ? false : \Errors\common_error::R("invalid request");
        $common_data = $this->FM->apply_filter_array($x, $this->get_common_filters());
        $this->FM->raise_array_error($common_data);
        $user_params = $this->FM->apply_filter_array($x, $this->get_user_filters());
        $this->FM->raise_array_error($user_params);
        // нужен класс queryBuilder-a
        $builder = \DB\SQLTools\SQLBuilder::F();
        $t = "@a" . md5(__METHOD__);
        if ($common_data['id']) {
            $builder->push("SET {$t}=:P{$builder->c}id;");
            $builder->push("UPDATE user SET
                login=:P{$builder->c}login, 
                role=:P{$builder->c}role,
                locked=:P{$builder->c}locked,
                is_approved=:P{$builder->c}is_approved,
                news=:P{$builder->c}news,
                phone_strip=:P{$builder->c}phone_strip    ,
                birth_date=:P{$builder->c}birth_date
              WHERE id={$t};");
            $builder->push_param(":P{$builder->c}id", $common_data['id']);
        } else {
            $builder->push("INSERT INTO user (guid,login,pass,role,locked,is_approved,`created`,birth_date,news,phone_strip) VALUES(UUID(),:P{$builder->c}login,'renew',
                :P{$builder->c}role,:P{$builder->c}locked,:P{$builder->c}is_approved,NOW(),:P{$builder->c}birth_date,:P{$builder->c}news,:P{$builder->c}phone_strip);");
            $builder->push("SET {$t} = LAST_INSERT_ID();");
        }
        $builder->push_params([
            ":P{$builder->c}login" => $common_data["login"],
            ":P{$builder->c}birth_date" => $common_data['birth_date']->format('Y-m-d 00:00:00'),
            ":P{$builder->c}role" => $common_data["role"],
            ":P{$builder->c}locked" => $common_data['locked'] ? 1 : 0,
            ":P{$builder->c}is_approved" => $common_data["is_approved"] ? 1 : 0,
            ":P{$builder->c}news" => $common_data["news"] ? 1 : 0,
            ":P{$builder->c}phone_strip" => \Filters\FilterManager::F()->apply_chain($user_params["phone"], ["Strip", "Trim", "PhoneMatch", "PhoneClear", "DefaultNull"]),
        ]);        
        $builder->inc_counter();

        $builder->push("INSERT INTO user__fields (id,name,family,eldername,phone) VALUES({$t},:P{$builder->c}name,:P{$builder->c}family,:P{$builder->c}eldername,:P{$builder->c}phone)
            ON DUPLICATE KEY UPDATE name=VALUES(name),eldername=VALUES(eldername),family=VALUES(family),phone=VALUES(phone);");
        $builder->push_params([
            ":P{$builder->c}name" => $user_params["name"], ":P{$builder->c}family" => $user_params['family'], ":P{$builder->c}eldername" => $user_params['eldername'],
            ":P{$builder->c}phone" => $user_params['phone']
        ]);
        $password_data = $this->FM->apply_filter_array($x, $this->get_password_filters());
        if ($password_data['password'] || $password_data['repassword']) {
            $password_data['password'] === $password_data['repassword'] ? false : \Errors\common_error::R("passwords does not match");
            if (mb_strlen($password_data['password'], "UTF-8") < 6) {
                \Errors\common_error::R("password too short");
            }
            $builder->inc_counter();
            $new_password = \Auth\UserInfo::encrypt_password($password_data['password']);
            $builder->push("UPDATE user SET pass=:P{$builder->c}pass WHERE id={$t};");
            $builder->push_param(":P{$builder->c}pass", $new_password);
        }
        $comment = $this->FM->apply_chain(array_key_exists("comment", $x) ? $x["comment"] : "", ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']);
        $builder->inc_counter();
        $builder->push("INSERT INTO user__comment(id,`comment`) VALUES({$t},:P{$builder->c}comment) ON DUPLICATE KEY UPDATE `comment`=VALUES(`comment`);");
        $builder->push_param(":P{$builder->c}comment", $comment);

        $builder->inc_counter();
        $builder->push("INSERT INTO user__wallet(id,money) VALUES({$t},:P{$builder->c}_money) ON DUPLICATE KEY UPDATE money=money+VALUES(money);");
        $builder->push_param(":P{$builder->c}_money", $user_params["money_delta"]);
        $builder->inc_counter();

        $user_id = $builder->execute_transact($t);

        $this->API_get_user($user_id);
    }

    protected function get_common_filters() {
        return[
            'id' => ['IntMore0', 'DefaultNull'],
            'login' => ['Strip', 'Trim', 'NEString', 'EmailMatch'],
            'birth_date' => ['DateMatch'],
            'role' => ['Strip', 'Trim', 'NEString'],
            'locked' => ['Boolean', 'DefaultFalse'],
            'is_approved' => ['Boolean', 'DefaultTrue'],
            'news' => ['Boolean', 'DefaultTrue'],
        ];
    }

    protected function get_user_filters() {
        return [
            'name' => ['Strip', 'Trim', 'NEString'],
            'family' => ['Strip', 'Trim', 'NEString', "DefaultEmptyString"],
            'eldername' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'phone' => ['Strip', 'Trim', 'NEString', 'Digits', 'PhoneMatch', 'DefaultNull'],
            'money_delta' => ["Float", "DefaultNull"],
        ];
    }

    protected function get_password_filters() {
        return [
            'password' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'repassword' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    protected function API_remove() {
        $user_id = $this->GP->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        $user_id ? FALSE : \Errors\common_error::R("invalid request");
        $user_id < 2 ? \Errors\common_error::R("cant remove system user") : false;
        $builder = \DB\SQLTools\SQLBuilder::F();
        $builder->push("DELETE FROM user WHERE id=:P;");
        $builder->push_param(":P", $user_id);
        $builder->execute_transact();
        $this->API_get_data();
    }

}
