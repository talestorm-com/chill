<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class ContentBlockController extends AbstractAdminController {

    protected function actionIndex() {
        $this->render_view("admin", "list");
    }

    protected function API_list() {
        \Content\ContentBlock\ContentBlockADVTLister::F($this->out)->run();
    }

    protected function API_get(int $p = null) {
        $id = $p?$p:$this->GP->get_filtered('id', ['IntMore0','DefaultNull']);
        $id?false:\Errors\common_error::R("invalid request");
        $cb = \Content\ContentBlock\ContentBlock::LI($id);
        $cb && !$cb->empty?false:\Errors\common_error::R("not found");
        $this->out->add('content_block', $cb->marshall());
        
    }

    protected function API_post() {
        $data = $this->GP->get_filtered('data', ['Trim','NEString','JSONString','NEArray','DefaultNull']);
        $data?false:\Errors\common_error::R("invalid request");
        $datamap = \DataMap\CommonDataMap::F()->rebind($data);
        $id = $datamap->get_filtered('id', ['IntMore0','DefaultNull']);
        $cb = null;
        if($id){
            $cb = \Content\ContentBlock\ContentBlock::LI($id);
            $cb->empty?\Errors\common_error::R("not found"):false;
        }else{
            $cb = \Content\ContentBlock\ContentBlock::F();
        }
        /* @var $cb \Content\ContentBlock\ContentBlock */
        $cb->load_from_datamap($datamap);
        $ret_id = $cb->save();
        $this->API_get($ret_id);
    }

    protected function API_remove() {
        $id_to_remove = $this->GP->get_filtered('id_to_remove',['IntMore0','DefaultNull']);
        $id_to_remove?false:\Errors\common_error::R("invalid request");
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM content_block WHerE id=:Pid")->push_param(":Pid", $id_to_remove)->execute_transact();
        \Content\ContentBlock\ContentBlock::RESET_CACHE();
        $this->API_list();
    }

}
