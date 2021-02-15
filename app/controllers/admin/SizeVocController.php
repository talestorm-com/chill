<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class SizeVocController extends \controllers\admin\AbstractAdminController {

    protected function actionIndex() {
        $this->render_view("admin", "list");
    }

    protected function actionDef() {
        $this->render_view("admin", "list_defs");
    }

    /**
     * возвращает список систем размеров
     */
    protected function API_metadata() {
        $this->out->add('size_system_voc', \DataModel\CatalogSizeDef\CatalogSizeDefVoc::F()->marshall());
    }

    protected function API_get_size_systems() {
        $condition = \ADVTable\Filter\FixedTokenFilter::F(NULL, [
                    'id' => 'Int:A.id',
                    'name' => 'String:A.name',
                    'short_name' => 'String:A.short_name',
                    'visible' => 'Int:A.visible',
        ]);
        $limit = \ADVTable\Limit\FixedTokenLimit::F();
        $direction = \ADVTable\Sort\FixedTokenSort::F(NULL, [
                    'id' => 'A.id',
                    'name' => 'A.name|A.id',
                    'short_name' => 'A.short_name|A.id',
                    'visible' => 'A.visible|A.id',
        ]);
        $direction->tokens_separator = '|';
        $queryf = "SELECT SQL_CALC_FOUND_ROWS * FROM catalog__size__alter__def A %s %s %s %s";
        $params = [];
        $counter = 0;
        $where = $condition->buildSQL($params, $counter);
        $query = sprintf($queryf, $condition->whereWord, $where, $direction->SQL, $limit->MySqlLimit);
        $items = \DB\DB::F()->queryAll($query, $params);
        $total = \DB\DB::F()->get_found_rows();
        if (!count($items) && $limit->page > 0) {
            $limit->setPage(0);
            $query = sprintf($queryf, $condition->whereWord, $where, $direction->SQL, $limit->MySqlLimit);
            $items = \DB\DB::F()->queryAll($query, $params);
            $total = \DB\DB::F()->get_found_rows();
        }
        $this->out->add('items', $items)->add('total', $total)->add('page', $limit->page)->add('perpage', $limit->perpage);
    }

    protected function API_get_size_system(int $rid = null) {
        $id = $rid ? $rid : $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? false : \Errors\common_error::R("invalid request");
        $row = \DB\DB::F()->queryRow("SELECT * FROM catalog__size__alter__def WHERE id=:Pid ", [":Pid" => $id]);
        $row ? FALSE : \Errors\common_error::R("not found");
        $this->out->add('data', $row);
    }

    protected function API_post_size_system() {
        $raw_data = $this->GP->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        $data_to_save = \Filters\FilterManager::F()->apply_filter_array($raw_data, $this->get_size_system_filters());
        \Filters\FilterManager::F()->raise_array_error($data_to_save);
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push_params([
            ":P{$b->c}name" => $data_to_save['name'],
            ":P{$b->c}short_name" => $data_to_save['short_name'],
            ":P{$b->c}visible" => $data_to_save['visible'],
            ":P{$b->c}info" => $data_to_save['info'],
            ":P{$b->c}html_mode" => $data_to_save['html_mode'],
        ]);
        $tn = "@a" . md5(__METHOD__);
        if ($data_to_save['id']) {
            $b->push_param(":P{$b->c}id", $data_to_save['id']);
            $b->push("SET {$tn} = :P{$b->c}id;");
            $b->push("UPDATE catalog__size__alter__def SET
                name=:P{$b->c}name,
                short_name=:P{$b->c}short_name,
                visible=:P{$b->c}visible,
                html_mode=:P{$b->c}html_mode,
                info=:P{$b->c}info
                WHERE id={$tn};
                ");
        } else {
            $b->push("INSERT INTO catalog__size__alter__def (name,short_name,visible,html_mode,info) 
                VALUES(:P{$b->c}name,:P{$b->c}short_name,:P{$b->c}visible,:P{$b->c}html_mode,:P{$b->c}info);");
            $b->push("SET {$tn} = LAST_INSERT_ID();");
        }
        $rid = $b->execute_transact($tn);
        \DataModel\CatalogSizeDef\CatalogSizeDefVoc::RESET_CACHE();
        $this->API_get_size_system($rid);
    }

    protected function API_remove_size_table() {
        $id = $this->GP->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        $id ? false : \Errors\common_error::R("invalid request");
        $builder = \DB\SQLTools\SQLBuilder::F();
        $builder->push("DELETE FROM catalog__size__alter__def WHERE id=:Pid");
        $builder->push_param(":Pid", $id);
        $builder->execute_transact();
        \DataModel\CatalogSizeDef\CatalogSizeDefVoc::RESET_CACHE();
        $this->API_get_size_systems();
    }

    protected function get_size_system_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'short_name' => ['Strip', 'Trim', 'NEString'],
            'visible' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'html_mode' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

    /**
     * возвращает список размеров c альтераторами
     */
    protected function API_get_size_list() {
        $sizes = \DB\DB::F()->queryAll("SELECT * FROM catalog__size__def");
        $this->out->add('sizes', is_array($sizes) ? $sizes : null);
        $aliases = \DB\DB::F()->queryAll("SELECT * FROM catalog__size__alter");
        $this->out->add('aliases', $aliases);
    }

    protected function API_post_sizes_table() {
        $items = $this->GP->get_filtered('data', ['Trim', 'JSONString', 'NEArray', 'DefaultNull']);
        $items ? false : \Errors\common_error::R("invalid request");
        \DataModel\CatalogSizeDef\Writer::F($items)->update();
        $this->API_get_size_list();
    }

}
