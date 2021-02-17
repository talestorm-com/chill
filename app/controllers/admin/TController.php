<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of TController
 *
 * @author eve
 */
class TController extends AbstractAdminController {

    //put your code here
    public function get_desktop_component_id() {
        return "desktop.translation";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    public function API_list() {
        $data = \DataMap\InputDataMap::F();
        $bridge = \DataMap\ADVTIDataBridge::F($data);
        $filter = \ADVTable\Filter\FixedTokenFilter::F($bridge, [
                    'section' => "String:A.section",
                    'token' => "String:A.token",
                    'translation' => "String:A.translation",
        ]);
        $limit = \ADVTable\Limit\FixedTokenLimit::F($bridge);
        $dir = \ADVTable\Sort\FixedTokenSort::F($bridge, [
                    'section' => 'A.section|A.token',
                    'token' => 'A.token|A.section',
                    'translation' => 'A.translation|A.token|A.section',
        ]);
        $dir->tokens_separator = '|';
        $qp = "SELECT SQL_CALC_FOUND_ROWS section,token,translation FROM lang__tokens A %s %s %s %s;";
        $c = 0;
        $p = [];
        $where = $filter->buildSQL($p, $c);
        $q = sprintf($qp, $filter->whereWord, $where, $dir->SQL, $limit->MySqlLimit);
        $items = \DB\DB::F()->queryAll($q, $p);
        if (!count($items) && $limit->page) {
            $limit->setPage(0);
            $q = sprintf($qp, $filter->whereWord, $where, $dir->SQL, $limit->MySqlLimit);
            $items = \DB\DB::F()->queryAll($q, $p);
        }
        $total = \DB\DB::F()->get_found_rows();
        $this->out->add('items', $items)->add('page', $limit->page)->add('perpage', $limit->perpage)
                ->add('total', $total);
    }

    public function API_set() {
        $section = \DataMap\InputDataMap::F()->get_filtered('section', ['Strip', 'Trim', 'NEString']);
        $name = \DataMap\InputDataMap::F()->get_filtered('name', ['Trim', 'NEString']);
        $value = \DataMap\InputDataMap::F()->get_filtered('value', ['Trim', 'NEString']);
        \Filters\FilterManager::F()->raise_array_error(compact('section', 'name', 'value'));
        $query = "UPDATE lang__tokens SET translation=:P WHERE section=:Ps AND token=:Pt ";
        $par =  [
            ':P' => $value, ':Ps' => $section, ':Pt' => $name
        ];
        \DB\DB::F()->exec($query,$par);
        \DB\errors\MySQLWarn::F(\DB\DB::F());
        $this->out->add('q', $query);
        $this->out->add('pp',$par);
        $this->out->add("new", $value);
    }

    public function API_remove() {
        $section = \DataMap\InputDataMap::F()->get_filtered('remove_section', ['Strip', 'Trim', 'NEString']);
        $name = \DataMap\InputDataMap::F()->get_filtered('remove_id', ['Trim', 'NEString']);
        \Filters\FilterManager::F()->raise_array_error(compact('section', 'name'));
        $query = "DELETE FROM lang__tokens WHERE section=:Ps AND token=:Pt";
        \DB\DB::F()->exec($query, [':Ps' => $section, ':Pt' => $name]);
        $this->API_list();
    }

    public function API_regen() {
        $path = \Config\Config::F()->WEB_ROOT . "assets" . DIRECTORY_SEPARATOR . "language" . DIRECTORY_SEPARATOR;
        \Helpers\Helpers::rm_files_by_regex($path, ["/\.json$/i"]);
    }

}
