<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of RibbonController
 *
 * @author eve
 */
class RibbonController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.ribbon";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        \Content\Ribbon\Lister::F()->run($this->out);
    }

    protected function API_post() {
        $writer = \Content\Ribbon\Writer\RibbonItemWriter::F(\DataMap\InputDataMap::F(), \DataMap\InputDataMap::F());
        $ret_id = $writer->run();
        $this->out->add("warnings", $writer->messages);
        $this->API_get($ret_id);
    }

    protected function API_get(int $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0"]);
        \Filters\FilterManager::F()->raise_array_error(compact('id'));
        $item = \Content\Ribbon\RibbonItem::F($id);
        $this->out->add('item', $item);
    }

}
