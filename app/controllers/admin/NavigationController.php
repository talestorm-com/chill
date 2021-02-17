<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class NavigationController extends \controllers\admin\AbstractAdminController {

    public function actionIndex() {
        $this->render_view("admin", "list");
    }

    //<editor-fold defaultstate="collapsed" desc="list">
    protected function API_get_data() {
        $limitation = \ADVTable\Limit\FixedTokenLimit::F();
        $condition = \ADVTable\Filter\FixedTokenFilter::F(null, [
                    'id' => 'Int:A.id',
                    'alias' => 'String:A.alias',
                    'description' => 'String:A.description',
        ]);

        $direction = \ADVTable\Sort\FixedTokenSort::F(NULL, [
                    'id' => 'A.id',
                    'alias' => 'A.alias|A.id',
                    'description' => 'A.description|A.id',
        ]);
        $direction->tokens_separator = "|";
        $p = [];
        $c = 0;
        $where = $condition->buildSQL($p, $c);
        $query = "SELECT SQL_CALC_FOUND_ROWS 
                A.*
          FROM 
          menu A
          %s %s %s %s;";
        $rows = \DB\DB::F()->queryAll(sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit), $p);
        if (!count($rows) && $limitation->page) {
            $limitation->setPage(0);
            $rows = \DB\DB::F()->queryAll(sprintf($query, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit), $p);
        }
        $total = \DB\DB::F()->get_found_rows();
        $this->out->add('total', $total)->add('items', $rows)->add('page', $limitation->page)->add('perpage', $limitation->perpage);
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="get">
    protected function API_get_menu(int $rid = null) {
        $id = $rid ? $rid : $this->GP->get_filtered("id", ['IntMore0', 'DefaultNull']);
        $id ? false : \Errors\common_error::R("invalid request");
        $row = \DB\DB::F()->queryRow("SELECT * FROM menu WHERE id=:P", [":P" => $id]);
        $row ? false : \Errors\common_error::R("not found");
        $row['tree'] = \MenuTree\MenuTree::F($id)->marshall();
        $this->out->add('data', $row);
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="post">
    protected function API_post_menu() {
        $x = \DataMap\GPDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'DefaultNull']);
        $x && is_array($x) ? false : \Errors\common_error::R("invalid request");
        $menu_data = $this->FM->apply_filter_array($x, $this->get_menu_filters());
        $this->FM->raise_array_error($menu_data);
        $builder = \DB\SQLTools\SQLBuilder::F();
        $t = "@a" . md5(__METHOD__);
        $builder->push_params([
            ":P{$builder->c}alias" => $menu_data['alias'],
            ":P{$builder->c}description" => $menu_data['description'],
            ":P{$builder->c}css" => $menu_data['css_class'],
        ]);
        if ($menu_data['id']) {
            $builder->push("SET {$t} = :P{$builder->c}id;");
            $builder->push_param(":P{$builder->c}id", $menu_data['id']);
            $builder->push("UPDATE menu SET alias = :P{$builder->c}alias, description=:P{$builder->c}description,css_class=:P{$builder->c}css WHERE id={$t};");
        } else {
            $builder->push("INSERT INTO menu (alias,css_class,description) VALUES(:P{$builder->c}alias,:P{$builder->c}css,:P{$builder->c}description);");
            $builder->push("SET {$t} = LAST_INSERT_ID();");
        }
        $builder->push("DELETE FROM menu__items WHERE menu_id={$t};");
        $this->generate_menu_inserts($builder, $menu_data['tree'], $t);
        $menu_id = $builder->execute_transact($t);
        \MenuTree\MenuTree::clear_dependency_beacon();
        $this->API_get_menu($menu_id);
    }

    //<editor-fold defaultstate="collapsed" desc="inserts">
    protected function generate_menu_inserts(\DB\SQLTools\SQLBuilder $builder, array $menu_data, $tn) {
        $builder->inc_counter();
        $ic = 0;
        $inserts = [];
        $pre_query = "INSERT INTO menu__items(id,menu_id,parent_id,sort_order,name,url,visible,css_class) VALUES ";
        $this->rec_create_items($inserts, $builder, $ic, $tn, $menu_data);
        $builder->push($pre_query . implode(",", $inserts));
        $builder->inc_counter();
    }

    protected function rec_create_items(array &$inserts, \DB\SQLTools\SQLBuilder $b, int &$c, string $tn, array $menu_items) {

        foreach ($menu_items as $raw_item) {
            $c++;
            $item = $this->FM->apply_filter_array($raw_item, $this->get_item_filters());
            $this->FM->raise_array_error($item);
            $inserts[] = "(:P{$b->c}_{$c}id,{$tn},:P{$b->c}_{$c}parent_id,:P{$b->c}_{$c}sort_order,:P{$b->c}_{$c}name,:P{$b->c}_{$c}url,:P{$b->c}_{$c}visible,:P{$b->c}_{$c}css)";
            $b->push_params([
                ":P{$b->c}_{$c}id" => $item['id'],
                ":P{$b->c}_{$c}parent_id" => $item['parent_id'],
                ":P{$b->c}_{$c}sort_order" => $item['sort_order'],
                ":P{$b->c}_{$c}name" => $item['name'],
                ":P{$b->c}_{$c}url" => $item['url'],
                ":P{$b->c}_{$c}visible" => $item['visible'],
                ":P{$b->c}_{$c}css" => $item['css_class'],
            ]);
            if (count($item['childs'])) {
                $this->rec_create_items($inserts, $b, $c, $tn, $item['childs']);
            }
        }
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="filters">
    protected function get_menu_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'alias' => ['Strip', 'Trim', 'NEString'],
            'description' => ['Strip', 'Trim', 'NEString'],
            'css_class' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'tree' => ['NEArray'],
        ];
    }

    protected function get_item_filters() {
        return [
            'id' => ['IntMore0'],
            'parent_id' => ['IntMore0', 'DefaultNull'],
            'sort_order' => ['AnyInt', 'Default0'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'url' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'visible' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'childs' => ['NEArray', 'DefaultEmptyArray'],
            'css_class' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    //</editor-fold>
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="delete">
    protected function API_remove() {
        $id = $this->GP->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        $id ? FALSE : \Errors\common_error::R("invalid request");
        $query = "DELETE FROM menu WHERE id=:P";
        \DB\DB::F()->exec($query, [":P" => $id]);
        $this->API_get_data();
    }

    //</editor-fold>
}
