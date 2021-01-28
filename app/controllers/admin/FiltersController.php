<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of FiltersController
 *
 * @author eve
 */
class FiltersController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.Filters";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        \Content\FilterPreset\Lister::F()->run($this->out);
    }

    protected function API_put() {
        $data = \DataMap\InputDataMap::F()->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? 0 : \Errors\common_error::R("invalid_request");
        $data_input = \DataMap\CommonDataMap::F()->rebind($data);
        $writer = \Content\FilterPreset\Writer\FilterPresetWriter::F($data_input, \DataMap\InputDataMap::F());
        $ret_id = $writer->run();
        $this->out->add("warnings", $writer->messages);
        $this->API_get($ret_id);
    }

    protected function API_get(int $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $item = \Content\FilterPreset\FilterPreset::F();
        $item->load($id);
        $this->out->add("preset", $item);
    }

    protected function _______on_before_api_action(string $action_method, string $action) {
        \ImageFly\MediaContextInfo::register_media_context(\Content\FilterPreset\FilterPreset::MEDIA_CONTEXT, 2600, 2600, 10, 10);
        return parent::on_before_api_action($action_method, $action);
    }

    protected function API_remove() {
        $id = \DataMap\InputDataMap::F()->get('id_to_remove', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        \Content\FilterPreset\Remover::F($id)->run();
        $this->API_list();
    }

}
