<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of StatusController
 *
 * @author eve
 */
class StatusController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.requests.status";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        \Content\RequestStatus\Lister::F(\DataMap\PostDataMap::F())->run($this->out);
    }

    protected function API_get(int $rid = null) {
        $id = $rid ? $rid : $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $row = \DB\DB::F()->queryRow("SELECT * FROM request__status WHERE id=:P", [":P" => $id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->out->add('data', $row);
    }

    protected function API_post() {
        $data = \DataMap\PostDataMap::F()->get_filtered("data", ["NEString", "JSONString", "NEArray", "DefaultNull"]);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $datamap = \DataMap\CommonDataMap::F()->rebind($data);
        $writer = \Content\RequestStatus\Writer\Writer::F($datamap, $datamap);
        $writer->run();
        $this->API_get($writer->result_id);
    }

    protected function API_remove() {
        $id_to_remove = \DataMap\GPDataMap::F()->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        if ($id_to_remove) {
            \DB\DB::F()->exec("DELETE FROM request__status WHERE id=:P", [":P" => $id_to_remove]);
        }

        $this->API_list();
    }

}
