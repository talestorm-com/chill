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
class AgeRestrictionListController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.agerestriction_list";
    }

    public function actionIndex() {
        //\ImageFly\MediaContextInfo::register_media_context(\AgeRestriction\AgeRestriction::MEDIA_CONTEXT, 1600, 1600, 20, 20);
        $this->render_view('admin', '../common_index');
    }

    public function API_list() {
        \AgeRestriction\Lister::F(\DataMap\PostDataMap::F())->run($this->out);
    }

    public function API_get(string $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered("id", ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $restriction = \AgeRestriction\AgeRestriction::F($id);       
        $restriction && $restriction->valid ? 0 : \Errors\common_error::R("not found");
        $this->out->add('data', $restriction);
        $this->API_langlist();
    }

    public function API_langlist() {
        $this->out->add('langs', \Language\LanguageList::F());
    }

    public function API_put() {
        $data = \DataMap\InputDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $ret_id = \AgeRestriction\AgeRestrictionWriter::F(\DataMap\CommonDataMap::F()->rebind($data))->run()->result_id;
        $this->API_get($ret_id);
    }

    public function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id_to_remove", ['IntMore0', 'DefaultNull']);
        if ($id) {
            \AgeRestriction\AgeRestrictionRemover::F($id)->run();
        }
        $this->API_list();
    }

}
