<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of GenreListController
 *
 * @author eve
 */
class TagListController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.tag_list";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    public function API_list() {
        \MediaTag\Lister::F(\DataMap\PostDataMap::F())->run($this->out);
    }

    public function API_get(string $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered("id", ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $genre = \MediaTag\MediaTag::F()->load($id);
        $genre && $genre->valid ? 0 : \Errors\common_error::R("not found");
        $this->out->add('data', $genre);
        $this->API_langlist();
    }

    public function API_langlist() {
        $this->out->add('langs', \Language\LanguageList::F());
    }

    public function API_put() {
        $data = \DataMap\InputDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $ret_id = \MediaTag\Writer::F(\DataMap\CommonDataMap::F()->rebind($data))->run()->id;
        $this->API_get($ret_id);
    }

    public function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id_to_remove", ['IntMore0', 'DefaultNull']);
        if ($id) {
            \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM media__content__tag WHERE id=:P")->push_param(":P", $id)->execute_transact();
        }
        $this->API_list();
    }

}
