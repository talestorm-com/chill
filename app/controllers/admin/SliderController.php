<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class SliderController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.slider";
    }

    public function actionIndex() {
        if (!\ImageFly\MediaContextInfo::F()->context_exists(\Content\Slider\Slider::MEDIA_CONTEXT)) {
            \ImageFly\MediaContextInfo::register_media_context(\Content\Slider\Slider::MEDIA_CONTEXT, 3600, 3600, 300, 300);
        }
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        \Content\Slider\ADVTLister::F($this->out)->run();
    }

    protected function API_get(int $p = null) {
        $id = $p ? $p : $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? false : \Errors\common_error::R("invalid request");
        $cb = \Content\Slider\Slider::LI($id);
        $this->out->add('slider', $cb->marshall());
        $this->API_get_layouts();
    }

    protected function API_post() {
        $data = $this->GP->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? false : \Errors\common_error::R("invalid request");
        $datamap = \DataMap\CommonDataMap::F()->rebind($data);
        $id = $datamap->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $cb = null;
        if ($id) {
            $cb = \Content\Slider\Slider::LI($id);
        } else {
            $cb = \Content\Slider\Slider::F();
        }
        /* @var $cb \Content\Slider\Slider */
        $cb->load_from_datamap($datamap);
        $ret_id = $cb->save();
        $this->API_get($ret_id);
    }

    protected function API_remove() {
        $id_to_remove = $this->GP->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        $id_to_remove ? false : \Errors\common_error::R("invalid request");
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM slider WHERE id=:Pid")->push_param(":Pid", $id_to_remove)->execute_transact();
        \ImageFly\ImageInfoManager::F()->remove_images(\Content\Slider\Slider::MEDIA_CONTEXT, $id_to_remove);
        \Content\Slider\Slider::RESET_CACHE();
        $this->API_list();
    }

    protected function API_get_layouts() {
        $this->out->add('layouts', \Content\Slider\LayoutEnumerator::C());
    }

}
