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
class PromoListController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.promocode_list";
    }

    public function actionIndex() {        
        $this->render_view('admin', '../common_index');
    }

    public function API_list() {
        \Promo\Lister::F(\DataMap\PostDataMap::F())->run($this->out);
    }

    public function API_get(string $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered("id", ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $promo = \Promo\Promo::F($id);       
        $promo && $promo->valid ? 0 : \Errors\common_error::R("not found");
        $this->out->add('data', $promo);        
    }

    

    public function API_put() {
        $data = \DataMap\InputDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $ret_id = \Promo\Writer::F(\DataMap\CommonDataMap::F()->rebind($data))->run()->result_id;
        $this->API_get($ret_id);
    }

    public function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id_to_remove", ['IntMore0', 'DefaultNull']);
        if ($id) {
            \Promo\Remover::F($id)->run();
        }
        $this->API_list();
    }

}
