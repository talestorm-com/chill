<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of MediaContentController
 *
 * @author eve
 */
class MediaPersonController extends AbstractAdminController {

    protected function actionIndex() {
        $this->render_view("admin", "list");
    }

    public function API_list() {

        \ImageFly\MediaContextInfo::register_media_context("media_person", 1600, 1600, 100, 100);
        \Content\MediaPerson\lister::F(\DataMap\PostDataMap::F())->run($this->out);
    }

    public function API_get(int $rid = null) {
        $id = $rid ? $rid : \DataMap\GPDataMap::F()->get_filtered('id', ["IntMore0", "DefaultNull"]);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $person = \Content\MediaPerson\MediaPerson::F($id);
        $this->out->add('data', $person);
    }

    public function API_put() {
        $data = \DataMap\PostDataMap::F()->get_filtered("data", ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"]);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $map = \DataMap\CommonDataMap::F()->rebind($data);
        $rid = \Content\MediaPerson\writer\Writer::F($map)->run()->result_id;
        $this->API_get($rid);
    }

    public function API_remove() {
        $id = \DataMap\GPDataMap::F()->get_filtered("id_to_remove", ["IntMore0", "DefaultNull"]);
        if ($id) {
            \Content\MediaPerson\Remover::F($id)->run();
        }
        $this->API_list();
    }

}
