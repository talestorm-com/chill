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
class ReviewsController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.reviews";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    public function API_list() {
        \Review\Lister::F(\DataMap\PostDataMap::F())->run($this->out);
    }

    public function API_get(int $mid = null, int $uid = null) {
        $media_id = $mid ? $mid : \DataMap\InputDataMap::F()->get_filtered("media_id", ['IntMore0', 'DefaultNull']);
        $media_id ? 0 : \Errors\common_error::R("invalid request");
        $user_id = $uid ? $uid : \DataMap\InputDataMap::F()->get_filtered("user_id", ['IntMore0', 'DefaultNull']);
        $user_id ? 0 : \Errors\common_error::R("invalid request");
        $review = \Review\Review::F()->load($media_id, $user_id);
        $review && $review->valid ? 0 : \Errors\common_error::R("not found");
        $this->out->add('data', $review);
    }

    public function API_put() {
        $data = \DataMap\InputDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $writer = \Review\Writer::F(\DataMap\CommonDataMap::F()->rebind($data));
        $writer->run();
        $this->API_get($writer->media_id, $writer->user_id);
    }

    public function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered("media_id_to_remove", ['IntMore0', 'DefaultNull']);
        $rid = \DataMap\InputDataMap::F()->get_filtered("user_id_to_remove", ['IntMore0', 'DefaultNull']);
        if ($id && $rid) {
            \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM media__content__review WHERE media_id=:P AND user_id=:PP")->push_param(":P", $id)->push_param(":PP", $rid)->execute_transact();
        }
        $this->API_list();
    }

}
