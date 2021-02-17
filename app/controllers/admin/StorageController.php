<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class StorageController extends AbstractAdminController {

    //<editor-fold defaultstate="collapsed" desc="html actions">
    public function get_desktop_component_id() {
        return "desktop.storage.storage";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    public function actionWarehouse() {
        $this->render_view('admin', 'warehouse_index');
    }

    public function actionOffline() {
        $this->render_view('admin', 'offline_index');
    }

    public function actionPartner() {
        $this->render_view('admin', 'partner_index');
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="storage apis">
    protected function API_get($rid = null) {
        $id = $rid ? $rid : $this->GP->get_filtered('id', ["IntMore0", "DefaultNull"]);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $query = "SELECT * FROM storage WHERE id=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $row && is_array($row) ? 0 : \Errors\common_error::R("not found");
        $this->out->add("storage", $row);
    }

    protected function API_put() {
        $data_raw = $this->GP->get_filtered("data", ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"]);
        $data_raw ? 0 : \Errors\common_error::R("invalid request");
        $data = \Filters\FilterManager::F()->apply_filter_array($data_raw, [
            'id' => ["IntMore0", "DefaultNull"],
            "guid" => ["Strip", "Trim", "NEString", "DefaultNull"],
            "name" => ["Strip", "Trim", "NEString"],
            "display_name" => ["Strip", "Trim", "NEString"],
            "visible" => ["Boolean", "DefaultTrue", "SQLBool"],
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        $b = \DB\SQLTools\SQLBuilder::F();
        $tn = "@a" . md5(__METHOD__);
        if ($data["id"]) {
            $b->push("SET {$tn}=:P{$b->c}id;");
            $b->push("UPDATE storage SET guid=:P{$b->c}guid,name=:P{$b->c}name,display_name=:P{$b->c}display_name,visible=:P{$b->c}visible
                WHERE id={$tn};");
            $b->push_param(":P{$b->c}id", $data["id"]);
        } else {
            $b->push("INSERT INTO storage (guid,name,display_name,visible) VALUES(:P{$b->c}guid,:P{$b->c}name,:P{$b->c}display_name,:P{$b->c}visible);");
            $b->push("SET {$tn} = LAST_INSERT_ID();");
        }
        $b->push_params([
            ":P{$b->c}guid" => $data["guid"],
            ":P{$b->c}name" => $data["name"],
            ":P{$b->c}display_name" => $data["display_name"],
            ":P{$b->c}visible" => $data["visible"],
        ]);
        $rid = $b->execute_transact($tn);
        \DB\errors\MySQLWarn::F($b->adapter);
        \Basket\OfflineShopList::reset_cache();
        $this->API_get($rid);
    }

    protected function API_list() {
        $condition = \ADVTable\Filter\FixedTokenFilter::F(NULL, [
        ]);
        $direction = \ADVTable\Sort\FixedTokenSort::F(NULL, [
        ]);
        $limitation = \ADVTable\Limit\FixedTokenLimit::F();

        $query = "SELECT SQL_CALC_FOUND_ROWS A.*,CASE WHEN B.storage_id IS NULL THEN 0 ELSE 1 END `primary`
            FROM storage A LEFT JOIN storage__flags B ON(A.id=B.storage_id AND B.pkey='PRIM') %s %s %s %s;
            ";
        $p = [];
        $c = 0;
        $where = $condition->buildSQL($p, $c);

        $r = sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit);
        $items = \DB\DB::F()->queryAll($r, $p);
        $total = \DB\DB::F()->get_found_rows();
        if (!count($items) && $total && $limitation->page) {
            $limitation->setPage(0);
            $r = sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit);
            $items = \DB\DB::F()->queryAll($r, $p);
            $total = \DB\DB::F()->get_found_rows();
        }
        $this->out->add('items', $items)->add('total', $total)->add("page", $limitation->page)->add('perpage', $limitation->perpage);
    }

    protected function API_remove() {
        $id = $this->GP->get_filtered("id_to_remove", ["IntMore0", "DefaultNull"]);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $query = "DELETE FROM storage WHERE id=:Pid";
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push($query);
        $b->push_param(":Pid", $id);
        $b->execute_transact();
        \DB\errors\MySQLWarn::F($b->adapter);
        \Basket\OfflineShopList::reset_cache();
        $this->API_list();
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="warehouse API">
    protected function API_warehouse() {
        $condition = \ADVTable\Filter\FixedTokenFilter::F(NULL, [
                    'storage_hash' => "String:SC.hash",
                    'id' => "Int:SC.product_id",
                    'guid' => 'String:P.guid',
                    'article' => 'String:P.article',
                    'name' => "String:PS.name",
                    'color_name' => 'String:CPCS.name',
                    "size_name" => "String:CSD.size",
                    "qty" => "Int:SC.qty",
                    "storage_name" => "Int:SC.storage_id",
        ]);
        $direction = \ADVTable\Sort\FixedTokenSort::F(null, [
                    "storage_hash" => "SC.hash",
                    'id' => 'SC.product_id|SC.hash',
                    'guid' => 'P.guid|SC.hash',
                    'storage_name' => 'S.name|SC.hash',
                    'article' => 'P.article|SC.hash',
                    "name" => "PS.name|SC.hash",
                    "color_name" => "CPCS.name|SC.hash",
                    "size_name" => "CSD.size|SC.hash",
                    "qty" => "SC.qty|SC.hash",
        ]);
        $direction->tokens_separator = "|";
        $limitation = \ADVTable\Limit\FixedTokenLimit::F();

        $params = [];
        $counter = 0;
        $where = $condition->buildSQL($params, $counter);
        $query = "
            SELECT SQL_CALC_FOUND_ROWS SC.hash storage_hash,SC.product_id id,S.name storage_name,
            P.guid,P.article,PS.name,CPCS.name color_name,CSD.size size_name,SC.qty            
                FROM storage__contents SC JOIN storage S ON(S.id=SC.storage_id)
                JOIN catalog__product P ON(P.id=SC.product_id)
                JOIN catalog__product__strings PS ON(PS.id=P.id)
                LEFT JOIN catalog__product__color__strings CPCS ON(CPCS.guid=SC.color)
                LEFT JOIN catalog__size__def CSD ON(CSD.id=SC.size)
                %s %s %s %s;";

        $rq = sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit);
        $items = \DB\DB::F()->queryAll($rq, $params);
        $total = \DB\DB::F()->get_found_rows();
        if (!count($items) && $limitation->page && $total) {
            $limitation->page = 0;
            $rq = sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit);
            $items = \DB\DB::F()->queryAll($rq, $params);
            $total = \DB\DB::F()->get_found_rows();
        }
        $this->out->add("items", $items)->add("total", $total)->add("page", $limitation->page)->add("perpage", $limitation->perpage);
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="offline shop api">
    protected function API_list_offline() {
        $query = "SELECT SQL_CALC_FOUND_ROWS
            A.id,A.name,A.address,A.visible,B.name storage_name            
            FROM storage__offline__shop A LEFT JOIN storage B ON(A.storage_id=B.id)
            %s %s %s %s";
        $condition = \ADVTable\Filter\FixedTokenFilter::F(null, [
                    'id' => "Int:A.id",
                    'name' => "String:A.name",
                    'address' => "String:A.address",
                    'storage_name' => 'Int:A.storage_id',
                    'visible' => 'Int:A.visible',
        ]);
        $direction = \ADVTable\Sort\FixedTokenSort::F(null, [
                    'id' => 'A.id',
                    'name' => 'A.name|A.id',
                    'address' => 'A.address|A.id',
                    'visible' => 'A.visible|A.id',
                    'storage_name' => 'B.name|A.id',
        ]);
        $direction->tokens_separator = "|";
        $limitation = \ADVTable\Limit\FixedTokenLimit::F(null);

        $p = [];
        $c = 0;
        $where = $condition->buildSQL($p, $c);

        $rq = sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit);
        $items = \DB\DB::F()->queryAll($rq, $p);
        $total = \DB\DB::F()->get_found_rows();
        if (!count($items) && $limitation->page) {
            $limitation->setPage(0);
            $rq = sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit);
            $items = \DB\DB::F()->queryAll($rq, $p);
            $total = \DB\DB::F()->get_found_rows();
        }
        $this->out->add("items", $items)->add("total", $total)->add("page", $limitation->page)->add("perpage", $limitation->perpage);
    }

    protected function API_get_offline($rid = null) {
        $id = $rid ? $rid : $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? false : \Errors\common_error::R("invalid request");
        $query = "SELECT A.*,B.name storage_name FROM  storage__offline__shop A LEFT JOIN storage B ON(A.storage_id=B.id) WHERE A.id=:P";
        $data = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $data && is_array($data) ? 0 : \Errors\common_error::R("not found");
        $this->out->add("offline", $data);
    }

    protected function API_put_offline() {
        $data_raw = $this->GP->get_filtered("data", ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"]);
        $data_raw ? 0 : \Errors\common_error::R("invalid request");
        $data = \Filters\FilterManager::F()->apply_filter_array($data_raw, [
            'id' => ["IntMore0", "DefaultNull"],
            "storage_id" => ["IntMore0", "DefaultNull"],
            "name" => ["Strip", "Trim", "NEString"],
            "address" => ["Strip", "Trim", "NEString", "DefaultNull"],
            "visible" => ["Boolean", "DefaultTrue", "SQLBool"],
            "lat" => ["Float", "DefaultNull"],
            "lon" => ["Float", "DefaultNull"],
            "email" => ["Strip", "Trim", "NEString", "EmailMatch", "DefaultNull"],
            "phone" => ["Strip", "Trim", "NEString", "PhoneMatch", "DefaultNull"],
            "phone_alter" => ["Strip", "Trim", "NEString", "PhoneMatch", "DefaultNull"],
            "works" => ["Strip", "Trim", "NEString", "DefaultNull"],
        ]);


        \Filters\FilterManager::F()->raise_array_error($data);
        $b = \DB\SQLTools\SQLBuilder::F();
        $t = "@a" . md5(__METHOD__);
        if ($data['id']) {
            $b->push("SET {$t}=:P{$b->c}id;");
            $b->push("UPDATE storage__offline__shop SET
                name=:P{$b->c}name,
                address=:P{$b->c}address,
                visible=:P{$b->c}visible,
                lat=:P{$b->c}lat,
                lon=:P{$b->c}lon,
                storage_id=:P{$b->c}storage_id ,
                email=:P{$b->c}email,
                phone=:P{$b->c}phone,
                phone_alter=:P{$b->c}phone_alter,                    
                works=:P{$b->c}works
               WHERE id={$t} ;");
            $b->push_param(":P{$b->c}id", $data["id"]);
        } else {
            $b->push("INSERT INTO storage__offline__shop (name,address,visible,lat,lon,storage_id,email,phone,phone_alter,works)
                VALUES(:P{$b->c}name,:P{$b->c}address,:P{$b->c}visible,:P{$b->c}lat,:P{$b->c}lon,:P{$b->c}storage_id,:P{$b->c}email,:P{$b->c}phone,:P{$b->c}phone_alter,:P{$b->c}works);");
            $b->push("SET {$t}=LAST_INSERT_ID();");
        }
        $b->push_params([
            ":P{$b->c}name" => $data["name"],
            ":P{$b->c}address" => $data["address"],
            ":P{$b->c}visible" => $data["visible"],
            ":P{$b->c}lat" => $data["lat"],
            ":P{$b->c}lon" => $data["lon"],
            ":P{$b->c}storage_id" => $data["storage_id"],
            ":P{$b->c}email" => $data['email'],
            ":P{$b->c}phone" => $data['phone'],
            ":P{$b->c}phone_alter" => $data['phone_alter'],                    
            ":P{$b->c}works" => $data['works']
        ]);
        $rid = $b->execute_transact($t);
        \DB\errors\MySQLWarn::F($b->adapter);
        \Basket\OfflineShopList::reset_cache();
        $this->API_get_offline($rid);
    }

    protected function API_remove_offline() {
        $id = $this->GP->get_filtered("id_to_remove", ["IntMore0", "DefaultNull"]);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $query = "DELETE FROM storage__offline__shop WHERE id=:Pid";
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push($query);
        $b->push_param(":Pid", $id);
        $b->execute_transact();
        \DB\errors\MySQLWarn::F($b->adapter);
        \Basket\OfflineShopList::reset_cache();
        $this->API_list_offline();
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="partner shop api">
    protected function API_list_partner() {
        $query = "SELECT SQL_CALC_FOUND_ROWS
            A.id,A.name,A.address,A.enabled,A.town
            FROM storage__partners A 
            %s %s %s %s";
        $condition = \ADVTable\Filter\FixedTokenFilter::F(null, [
                    'id' => "Int:A.id",
                    'name' => "String:A.name",
                    'address' => "String:A.address",
                    'town' => "String:A.town",
                    'visible' => 'Int:A.enabled',
        ]);
        $direction = \ADVTable\Sort\FixedTokenSort::F(null, [
                    'id' => 'A.id',
                    'name' => 'A.name|A.id',
                    'address' => 'A.address|A.id',
                    'town' => 'A.town|A.id',
                    'visible' => 'A.enabled|A.id',
        ]);
        $direction->tokens_separator = "|";
        $limitation = \ADVTable\Limit\FixedTokenLimit::F(null);

        $p = [];
        $c = 0;
        $where = $condition->buildSQL($p, $c);

        $rq = sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit);
        $items = \DB\DB::F()->queryAll($rq, $p);
        $total = \DB\DB::F()->get_found_rows();
        if (!count($items) && $limitation->page) {
            $limitation->setPage(0);
            $rq = sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit);
            $items = \DB\DB::F()->queryAll($rq, $p);
            $total = \DB\DB::F()->get_found_rows();
        }
        $this->out->add("items", $items)->add("total", $total)->add("page", $limitation->page)->add("perpage", $limitation->perpage);
    }

    protected function API_get_partner($rid = null) {
        $id = $rid ? $rid : $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? false : \Errors\common_error::R("invalid request");
        $query = "SELECT A.* FROM  storage__partners A WHERE A.id=:P";
        $data = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $data && is_array($data) ? 0 : \Errors\common_error::R("not found");
        $this->out->add("partner", $data);
    }

    protected function API_put_partner() {
        $data_raw = $this->GP->get_filtered("data", ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"]);
        $data_raw ? 0 : \Errors\common_error::R("invalid request");
        $data = \Filters\FilterManager::F()->apply_filter_array($data_raw, [
            'id' => ["IntMore0", "DefaultNull"],
            "name" => ["Strip", "Trim", "NEString"],
            "address" => ["Strip", "Trim", "NEString", "DefaultNull"],
            "enabled" => ["Boolean", "DefaultTrue", "SQLBool"],
            "lat" => ["Float", "DefaultNull"],
            "lon" => ["Float", "DefaultNull"],
            "email" => ["Strip", "Trim", "NEString", "EmailMatch", "DefaultNull"],
            "phone" => ["Strip", "Trim", "NEString", "PhoneMatch", "DefaultNull"],
            "phone_alter" => ["Strip", "Trim", "NEString", "PhoneMatch", "DefaultNull"],
            "works" => ["Strip", "Trim", "NEString", "DefaultNull"],
            "town" => ["Strip", "Trim", "NEString",],
        ]);


        \Filters\FilterManager::F()->raise_array_error($data);
        $b = \DB\SQLTools\SQLBuilder::F();
        $t = "@a" . md5(__METHOD__);
        if ($data['id']) {
            $b->push("SET {$t}=:P{$b->c}id;");
            $b->push("UPDATE storage__partners SET
                name=:P{$b->c}name,
                address=:P{$b->c}address,
                enabled=:P{$b->c}enabled,
                lat=:P{$b->c}lat,
                lon=:P{$b->c}lon,                
                email=:P{$b->c}email,
                phone=:P{$b->c}phone,
                phone_alter=:P{$b->c}phone_alter,
                works=:P{$b->c}works,
                town=:P{$b->c}town
               WHERE id={$t} ;");
            $b->push_param(":P{$b->c}id", $data["id"]);
        } else {
            $b->push("INSERT INTO storage__partners (name,address,enabled,lat,lon,email,phone,phone_alter,works,town)
                VALUES(:P{$b->c}name,:P{$b->c}address,:P{$b->c}enabled,:P{$b->c}lat,:P{$b->c}lon,:P{$b->c}email,:P{$b->c}phone,:P{$b->c}phone_alter,:P{$b->c}works,:P{$b->c}town);");
            $b->push("SET {$t}=LAST_INSERT_ID();");
        }
        $b->push_params([
            ":P{$b->c}name" => $data["name"],
            ":P{$b->c}address" => $data["address"],
            ":P{$b->c}enabled" => $data["enabled"],
            ":P{$b->c}lat" => $data["lat"],
            ":P{$b->c}lon" => $data["lon"],
            ":P{$b->c}email" => $data['email'],
            ":P{$b->c}phone" => $data['phone'],
            ":P{$b->c}phone_alter" => $data['phone_alter'],
            ":P{$b->c}works" => $data['works'],
            ":P{$b->c}town" => $data['town'],
        ]);
        $rid = $b->execute_transact($t);
        \DB\errors\MySQLWarn::F($b->adapter);
        \Basket\PartnerList::reset_cache();
        $this->API_get_partner($rid);
    }

    protected function API_remove_partner() {
        $id = $this->GP->get_filtered("id_to_remove", ["IntMore0", "DefaultNull"]);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $query = "DELETE FROM storage__partners WHERE id=:Pid";
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push($query);
        $b->push_param(":Pid", $id);
        $b->execute_transact();
        \DB\errors\MySQLWarn::F($b->adapter);
        \Basket\PartnerList::reset_cache();
        $this->API_list_partner();
    }

    //</editor-fold>
}
