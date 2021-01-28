<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of StickerController
 *
 * @author eve
 */
class ChillCommentController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.CommentList";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        \Content\ChillComment\Lister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_get(int $p = null) {
        $id = $p ? $p : $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? false : \Errors\common_error::R("invalid request");
        $cb = \Content\ChillComment\ChillCommentItem::F($id);

        $cb && $cb->id ? 0 : \Errors\common_error::R("not found");
        $this->out->add('data', $cb);
    }

    protected function API_put() {
        $data = \DataMap\InputDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $ret_id = \Content\ChillComment\Writer::F(\DataMap\CommonDataMap::F()->rebind($data))->run()->operation_id;
        $this->API_get($ret_id);
    }

    protected function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id_to_remove", ['IntMore0', 'DefaultNull']);
        if ($id) {
            \DB\DB::F()->exec("DELETE FROM chill__review WHERE id=:P",[":P"=>$id]);
            \Content\ChillComment\ChillCommentItem::reset_cache();
        }
        $this->API_list();
    }

    

}
