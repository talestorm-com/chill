<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class FallbackImageController extends AbstractAdminController {

    const MEDIA_CONTEXT = "fallback";

    public function get_desktop_component_id() {
        return "desktop.FallbackImages";
    }

    public function actionIndex() {
        if (!\ImageFly\MediaContextInfo::F()->context_exists(static::MEDIA_CONTEXT)) {
            \ImageFly\MediaContextInfo::register_media_context(static::MEDIA_CONTEXT, 3600, 3600, 100, 100);
        }
        $this->render_view('admin', '../common_index');
    }
    
    
    protected function API_get_list(){
        $this->out->add("contexts", \ImageFly\MediaContextInfo::F()->list_contexts());
    }

}
