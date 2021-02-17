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
class TRController extends AbstractAdminController {

    //put your code here
    public function get_desktop_component_id() {
        return "desktop.translation_toks";
    }

    public function actionIndex() {
        
        $this->render_view('admin', '../common_index');
    }

    public function API_list() {
        $data = \DataMap\InputDataMap::F();
        $bridge = \DataMap\ADVTIDataBridge::F($data);
        $filter = \ADVTable\Filter\FixedTokenFilter::F($bridge, [
                    'language_id' => "String:B.language_id",
                    'literal' => "String:A.l",
                    'translation' => "String:B.t",
                    'default_translation' => "String:A.t",
        ]);
        $limit = \ADVTable\Limit\FixedTokenLimit::F($bridge);
        $dir = \ADVTable\Sort\FixedTokenSort::F($bridge, [
                    'language_id' => 'B.language_id|A.l',
                    'literal' => 'A.l|B.language_id',
                    'default_translation' => "A.t|A.l|B.language_id",
                    'translation' => 'B.t|A.l|B.language_id',
        ]);
        $dir->tokens_separator = '|';
        $qp = "SELECT SQL_CALC_FOUND_ROWS 
            B.language_id, A.l `literal` ,A.t default_translation,B.t translation,A.language_id ddlang
            FROM chill__frontend__language A LEFT JOIN chill__frontend__language B ON(A.l=B.l AND A.language_id='ru')
            %s %s %s %s;";
        $c = 0;
        $p = [];
        $filter->addDirectCondition('(A.language_id=\'ru\')');
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

        // токены русского языка всегда содержат полный набор
    }

    public function API_set() {
        $section = \DataMap\InputDataMap::F()->get_filtered('language_id', ['Strip', 'Trim', 'NEString']);
        $name = \DataMap\InputDataMap::F()->get_filtered('literal', ['Trim', 'NEString']);
        $value = \DataMap\InputDataMap::F()->get_filtered('translation', ['Trim', 'NEString']);
        \Filters\FilterManager::F()->raise_array_error(compact('section', 'name', 'value'));
        $query = "UPDATE chill__frontend__language SET t=:P WHERE language_id=:Ps AND l=:Pt ";
        $par = [
            ':P' => $value, ':Ps' => $section, ':Pt' => $name
        ];
        \DB\DB::F()->exec($query, $par);
        \DB\errors\MySQLWarn::F(\DB\DB::F());
        $this->out->add('q', $query);
        $this->out->add('pp', $par);
        $this->out->add("new", $value);
    }

    public function API_remove() {
        $section = \DataMap\InputDataMap::F()->get_filtered('remove_language', ['Strip', 'Trim', 'NEString']);
        $name = \DataMap\InputDataMap::F()->get_filtered('remove_literal', ['Trim', 'NEString']);
        \Filters\FilterManager::F()->raise_array_error(compact('section', 'name'));
        $query = "DELETE FROM chill__frontend__language WHERE language_id=:Ps AND l=:Pt";
        \DB\DB::F()->exec($query, [':Ps' => $section, ':Pt' => $name]);
        $this->API_list();
    }

    public function API_regen() {
        \Language\LanguageTokenList::reset_cache();
    }

}
