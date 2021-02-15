<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of CDNAPIController
 *
 * @author eve
 */
class CDNAPIController extends AbstractAdminController {

    protected function API_list() {
        $path = \DataMap\InputDataMap::F()->get_filtered("path", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $path ? 0 : $path = "/";
        $request = \CDN_DRIVER\CDNListRequest::F()->set_path($path);
        $request->run();
        if ($request->success) {
            $this->out->add("cdnapi", [
                'path' => $request->path,
                'files' => $request->result,
            ]);
        }
        $this->out->add("cdn_request_dump", print_r($request, true));
    }

    protected function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if ($id) {
            $request = \CDN_DRIVER\CDNRemoveRequest::F();
            $request->run($id);
            $this->out->add("cdn_delete_dump", print_r($request, true));
        }
        $this->API_list();
    }

    protected function API_mkdir() {
        $path = \DataMap\InputDataMap::F()->get_filtered("path", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $path ? 0 : $path = "/";
        $name = \DataMap\InputDataMap::F()->get_filtered("name", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $name ? 0 : \Errors\common_error::R("invalid_request");
        $request = \CDN_DRIVER\CDNMKDirRequest::F();
        $request->set_path($path);
        $request->run($name);
        $this->API_list();
    }

    protected function API_info() {
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $request = \CDN_DRIVER\CDNInfoRequest::F();
        $request->run($id);
        $this->out->add('cdnapi', ['id' => $id, 'info' => $request->result]);
        // $this->out->add("cdn_info_dump", print_r($request, true));
    }

    protected function API_get_uploader() {
        $context = \DataMap\InputDataMap::F()->get_filtered("context", ["Strip", "Trim", "NEString", "DefaultNull"]);
        $context ? 0 : \Errors\common_error::R("context required");
        $method = "API_get_uploader_{$context}";
        !method_exists($this, $method) ? \Errors\common_error::RF("no uploader method for context %s in %s", $context, __METHOD__) : 0;
        $this->$method();
    }

    protected function API_get_uploader_ctVIDEO() {
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $request = \Router\Request::F();
        $url = [
            $request->https ? "https:/" : "http:/",
            $request->host,
            "admin", "CDNAPI", "uploader_ctVIDEO?id={$id}"
        ];
        $this->out->add("url", implode("/", $url));
    }

    protected function actionUploader_ctVIDEO() {
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        \smarty\SMW::F()->smarty->assign("content_id", $id);
        $this->out->add_css("/assets/css/CDNAPIController/uploader.css");
        $this->render_view("CDNAPIController/layout", "ctVIDEO");
    }

    protected function API_request_url() {
        $this->out->add("url", \CDN_DRIVER\CDNUrlMaker::mk_url("POST", "objects"));
    }

    protected function API_subscribe_request() {
        $this->out->add("url", \CDN_DRIVER\CDNUrlMaker::mk_url("POST", "objects"));
        $this->out->add("path", \CDN_DRIVER\CDNPathRecoverer::recover_path(\DataMap\InputDataMap::F()->get_filtered('path', ['Strip', 'Trim', 'NEString'])));
    }

    protected function API_transcoder() {
        $request = \CDN_DRIVER\CDNPresetsRequest::F();
        $request->run("");
        $this->out->add('cdnapi', ['id' => null, 'info' => $request->result]);
    }

    protected function API_transcoder_go() {
        //\Errors\common_error::R("disabled");
        $cdn_id = \DataMap\InputDataMap::F()->get_filtered('cdn_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $cdn_id ? 0 : \Errors\common_error::R("no cdn_id");
        $request = \CDN_DRIVER\CDNTranscoderRequest::F();
        $request->run($cdn_id);
        $this->out->add('cdnapi', ['id' => null, 'info' => $request->result]);
    }

    protected function API_transcode_all() {
        \Errors\common_error::R("disabled");
        \CDN_DRIVER\TranscoderTask::mk_params()->run();
    }

    protected function API_transcode_all2() {
        \Errors\common_error::R("disabled");
        \CDN_DRIVER\TranscoderTask2::mk_params()->run();
    }

    protected function API_encoding_try() {
        die('disabled');
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if ($id) {
            $this->out->add('enc_id', $id);
            \CDN_DRIVER\CDNEncoderTask::mk_params()->run(['id' => $id]);
        }
    }
    protected function API_encoding_try_v2() {        
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if ($id) {
            $this->out->add('enc_id', $id);
            \CDN_DRIVER\CDNEncoderTaskV4::mk_params()->run(['id' => $id]);
        }
    }

    protected function API_transcoder_all() {
        die('disabled');
        \CDN_DRIVER\TranscoderTaskALL::mk_params()->run();
    }

}
