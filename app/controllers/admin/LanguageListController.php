<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of LanguageListController
 *
 * @author eve
 */
class LanguageListController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.language_list";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    public function API_list() {
        \Language\LanguageLister::F(\DataMap\PostDataMap::F())->run($this->out);
    }

    public function API_get(string $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered("id", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $lang = array_key_exists($id, \Language\LanguageList::F()->index) ? \Language\LanguageList::F()->get_language($id) : \Errors\common_error::R("not found");
        $this->out->add('data', $lang);
    }

    public function API_put() {
        // regen tables
        $data = \DataMap\InputDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $ret_id = \Language\LanguageWriter::F(\DataMap\CommonDataMap::F()->rebind($data))->run()->operation_id;
        \Language\LanguageList::reset_cached();
        $this->API_get($ret_id);
    }

    public function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id_to_remove", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if ($id) {
            \Language\LanguageList::reset_cached();
            $language = \Language\LanguageList::F()->get_language($id);
            if ($language) {
                \Language\LanguageTablesManager::F()->remove_language_tables($language->id);
                \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM language__language WHERE id=:P")->push_param(":P", $language->id)->execute();
                \Language\LanguageList::reset_cached();
            }
        }
        $this->API_list();
    }

    public function API_fix() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $lang = array_key_exists($id, \Language\LanguageList::F()->index) ? \Language\LanguageList::F()->get_language($id) : null;
        if ($lang) {
            /* @var $lang \Language\LanguageItem */
            \Language\LanguageTablesManager::F()->mk_language_tables($lang->id, false);
        }
    }

}
