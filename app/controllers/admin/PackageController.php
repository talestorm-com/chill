<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of TrainingHallController
 *
 * @author eve
 */
class PackageController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.packageList";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        \Content\ClientPackage\Lister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_get(int $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $hal = \Content\ClientPackage\Package::F($id);        
        $this->out->add('package', $hal);        
    }

    protected function API_post() {
        $input = \DataMap\InputDataMap::F();
        $json = $input->get_filtered("data", ["NEString", "JSONString", "DefaultNull"]);
        $json ? 0 : \Errors\common_error::R("invalid input");
        $data_input = \DataMap\CommonDataMap::F()->rebind($json);
        $rid = \Content\ClientPackage\writer\writer::F($data_input, $input)->run();
        $this->API_get($rid);
    }

    protected function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id_to_remove", ["IntMore0", "DefaultNull"]);
        if ($id) {
            \Content\ClientPackage\Remover::F()->run($id);
        }
        $this->API_list();
    }

}
