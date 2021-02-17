<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\MediaAPI;

class ImageFlyController extends \controllers\abstract_controller {

    protected $require_admin_permission = [
        'upload' => 'upload', 'source' => 'source','DownloadSource'=>'DownloadSource',
        'API' => 'API'
    ];

    /** аплоады нужно прикрыть */
    protected function on_before_method($requested_action, &$call_method_name) {
        if (array_key_exists(mb_strtolower($requested_action, 'UTF-8'), $this->require_admin_permission)) {
            if (!($this->check_access() && \Auth\Auth::F()->is(\Auth\Roles\RoleEmployer::class))) {
                $this->clear_content_buffer();
                if (!headers_sent()) {
                    header('HTTP/1.0 403 Forbidden');
                }
                die('access denied');
            }
        }
        parent::on_before_method($requested_action, $call_method_name);
    }

    protected function API_convert_all() {
        die("closed entrypoint");
        $params = \ImageFly\TaskConvertSources::mk_params();
        /* @var $params \AsyncTask\AsyncTaskParams */
        $params->run();
    }

    protected function API_get_image_info(string $acontext = null, string $aowner_id = null, string $aimage = null) {
        $context = $acontext ? $acontext : $this->GP->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $owner_id = $aowner_id ? $aowner_id : $this->GP->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $image = $aimage ? $aimage : $this->GP->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $context && $image && ($owner_id || $context === "_color") ? false : \Errors\common_error::R("invalid request");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        $result = \ImageFly\ImageInfoManager::F()->get_image_info($context, ($owner_id ? $owner_id : ($context === "_color" ? 100 : 0)), $image);
        if ($result && $result->valid) {
            $this->out->add('image_info', $result);
        } else {
            \Errors\common_error::R("not found");
        }
    }

    protected function API_get_image_info_v2(string $acontext = null, string $aowner_id = null, string $aimage = null) {
        $context = $acontext ? $acontext : $this->GP->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $owner_id = $aowner_id ? $aowner_id : $this->GP->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $image = $aimage ? $aimage : $this->GP->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $context && $image && ($owner_id || $context === "_color") ? false : \Errors\common_error::R("invalid request");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        $result = \ImageFly\ImageInfoManager::F()->get_image_info_v2($context, ($owner_id ? $owner_id : ($context === "_color" ? 100 : 0)), $image);
        $this->out->add('image_info', $result);
    }

    protected function API_remove_image() {
        $context = $this->GP->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $owner_id = $this->GP->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $image = $this->GP->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $context && $image && $owner_id ? false : \Errors\common_error::R("invalid request");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        \ImageFly\ImageFly::F()->remove_image($context, $owner_id, $image);
        $this->API_list($context, $owner_id);
    }

    protected function API_remove_color() {
        $context = "_color";
        $image = $this->GP->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $context && $image ? false : \Errors\common_error::R("invalid request");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        \ImageFly\ImageFly::F()->remove_color($image);
    }

    protected function API_post_image_title() {
        $all_data = $this->GP->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $all_data ? false : \Errors\common_error::R("invalid_request");
        $D = \DataMap\CommonDataMap::F()->rebind($all_data);
        $context = $D->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $owner_id = $D->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $image = $D->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $title = $D->get_filtered("title", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $context && $image && $owner_id ? false : \Errors\common_error::R("invalid request");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        \ImageFly\ImageInfoManager::F()->set_image_title($context, $owner_id, $image, $title);
        $this->API_get_image_info($context, $owner_id, $image);
    }

    protected function API_post_image_title_wef() {
        $all_data = $this->GP->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $all_data ? false : \Errors\common_error::R("invalid_request");
        $D = \DataMap\CommonDataMap::F()->rebind($all_data);
        $context = $D->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $owner_id = $D->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $image = $D->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $title = $D->get_filtered("title", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $extended_params = $D->get_filtered('properties', ['NEArray', 'DefaultNull']);
        $context && $image && $owner_id ? false : \Errors\common_error::R("invalid request");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        \ImageFly\ImageInfoManager::F()->set_image_title($context, $owner_id, $image, $title);
        $extended_params ? \ImageFly\ImageInfoManager::F()->append_image_properties($context, $owner_id, $image, $extended_params) : null;
        $this->API_get_image_info($context, $owner_id, $image);
    }

    protected function actionUpload() {
        //context,image
        //спецконтекст temp - для временного хранения
        //вынос из temp через неделю            
        try {
            $callback = $this->GP->get_filtered('callback', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $callback ? FALSE : \Errors\common_error::R("no callback name defined");
            $this->out->add('callback_name', $callback);
            $log = \ImageFly\ImageFly::F()->handle_upload(); //нужен переброс по фреймстатусу оверрайд рендерера?            
            $this->out->add('upload_log', $log);
            $this->out->add('upload_screen', ob_get_clean());
            try {
                $this->API_list();
            } catch (\Exception $e) {
                
            }
        } catch (\Exception $ee) {
            $this->out->add('upload_error', ['message' => $ee->getMessage(), 'file' => $ee->getFile(), 'line' => $ee->getLine(), 'trace' => $ee->getTraceAsString()]);
        }
        $this->render_view('media_uploader', 'result');
    }

    protected function API_list(string $acontext = null, string $aowner_id = null) {
        $context = $acontext ? $acontext : $this->GP->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $owner_id = $aowner_id ? $aowner_id : $this->GP->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $context && $owner_id ? false : \Errors\common_error::R("invalid request");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        $this->out->add('list', \ImageFly\ImageInfoManager::F()->list_image_marshall($context, $owner_id));
    }

    protected function actionGet_image() {
        try {
            $result = \ImageFly\ImageFly::F()->parse_request($this->GP->get('rqi', null));
            $image = \ImageFly\ImageFly::F()->get_image_request($result);
            $this->clear_content_buffer();
            if (is_string($image) && file_exists($image)) {
                if (!headers_sent()) {
                    header("Content-Type: image/{$result->mime}");
                }
                readfile($image);
                die();
            }
            if (!headers_sent()) {
                header("Content-Type: image/{$result->mime}");
            }
            echo $image->getimageblob();
        } catch (\ImageFly\MailformedImageSpec $e) {
            \Router\Router::F()->redirect($e->redirect_url, 301);
        } catch (\Exception $e) {
            if (!headers_sent()) {
                header("HTTP/1.0 404 Not Found");
            }
            $this->clear_content_buffer();
            echo $e->getMessage();
        }
        die();
    }

    protected function actionDownloadSource() {
        try {
            $context = $this->GP->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $owner_id = $this->GP->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $image = $this->GP->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $context && $image && $owner_id ? false : \Errors\common_error::R("invalid request");
            \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
            $image_file = \ImageFly\ImageFly::F()->get_image_source_filename($context, $owner_id, $image);
            $this->clear_content_buffer();

            if (!headers_sent()) {
                header("Content-Type: image/jpeg");
                header('Content-Disposition: attachment; filename="' . $image . '.jpg"');
            }
            readfile($image_file);
        } catch (\Exception $e) {
            if (!headers_sent()) {
                header("HTTP/1.0 404 Not Found");
            }
            $this->clear_content_buffer();
            echo $e->getMessage();
        }
        die();
    }

    protected function actionSource() {
        try {
            $context = $this->GP->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            if ($context === "_color") {
                return $this->actionSourceColor();
            }
            $owner_id = $this->GP->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $image = $this->GP->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $context && $image && $owner_id ? false : \Errors\common_error::R("invalid request");
            \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
            $image = \ImageFly\ImageFly::F()->get_image_source($context, $owner_id, $image);
            $this->clear_content_buffer();

            if (!headers_sent()) {
                header("Content-Type: " . $image->getimagemimetype());
            }
            echo $image->getimageblob();
        } catch (\Exception $e) {
            if (!headers_sent()) {
                header("HTTP/1.0 404 Not Found");
            }
            $this->clear_content_buffer();
            echo $e->getMessage();
        }
        die();
    }

    protected function actionSourceColor() {
        try {
            $context = "_color";
            $image = $this->GP->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $context && $image ? false : \Errors\common_error::R("invalid request");
            \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
            $image = \ImageFly\ImageFly::F()->get_color_source($image);
            $this->clear_content_buffer();
            if (!headers_sent()) {
                header("Content-Type: " . $image->getimagemimetype());
            }
            echo $image->getimageblob();
        } catch (\Exception $e) {
            if (!headers_sent()) {
                header("HTTP/1.0 404 Not Found");
            }
            $this->clear_content_buffer();
            echo $e->getMessage();
        }
        die();
    }

    protected function API_post_image_crop() {
        $image_info = $this->GP->get_filtered('image_info', ['NEArray', 'DefaultEmptyArray']);
        $image_data = \DataMap\CommonDataMap::F()->rebind($image_info);
        $context = $image_data->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $owner_id = $image_data->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $image = $image_data->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $context && $image && ($owner_id || $context === "_color") ? false : \Errors\common_error::R("invalid request");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        $crop_start_x = $image_data->get_filtered('crop_start_x', ['Float', 'DefaultNull']);
        $crop_start_y = $image_data->get_filtered('crop_start_y', ['Float', 'DefaultNull']);
        $crop_end_x = $image_data->get_filtered('crop_end_x', ['Float', 'DefaultNull']);
        $crop_end_y = $image_data->get_filtered('crop_end_y', ['Float', 'DefaultNull']);
        if ($context === "_color") {
            \ImageFly\ImageInfoManager::F()->set_color_crop($image, $crop_start_x, $crop_start_y, $crop_end_x, $crop_end_y);
            \ImageFly\ImageFly::F()->delete_color_cache($image);
            $this->API_get_image_info($context, $owner_id, $image);
        } else {
            \ImageFly\ImageInfoManager::F()->set_image_crop($context, $owner_id, $image, $crop_start_x, $crop_start_y, $crop_end_x, $crop_end_y);
            \ImageFly\ImageFly::F()->delete_image_cache($context, $owner_id, $image);
            $this->API_get_image_info($context, $owner_id, $image);
        }
    }

    protected function API_post_image_crop_v2() {
        $image_info = $this->GP->get_filtered('image_info', ['NEArray', 'DefaultEmptyArray']);
        $image_data = \DataMap\CommonDataMap::F()->rebind($image_info);
        $context = $image_data->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $owner_id = $image_data->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $image = $image_data->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $context && $image && ($owner_id || $context === "_color") ? false : \Errors\common_error::R("invalid request");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        $items = $image_data->get_filtered('items', ['NEArray', 'DefaultEmptyArray']);

        //$crop_start_x = $image_data->get_filtered('crop_start_x', ['Float', 'DefaultNull']);
        //$crop_start_y = $image_data->get_filtered('crop_start_y', ['Float', 'DefaultNull']);
        //$crop_end_x = $image_data->get_filtered('crop_end_x', ['Float', 'DefaultNull']);
        //$crop_end_y = $image_data->get_filtered('crop_end_y', ['Float', 'DefaultNull']);
        if ($context === "_color") {
            //  \ImageFly\ImageInfoManager::F()->set_color_crop($image, $crop_start_x, $crop_start_y, $crop_end_x, $crop_end_y);
            //  \ImageFly\ImageFly::F()->delete_color_cache($image);
            //  $this->API_get_image_info($context, $owner_id, $image);
        } else {
            \ImageFly\ImageInfoManager::F()->set_image_crop_v2($context, $owner_id, $image, $items);
            //\ImageFly\ImageInfoManager::F()->set_image_crop($context, $owner_id, $image, $crop_start_x, $crop_start_y, $crop_end_x, $crop_end_y);
            \ImageFly\ImageFly::F()->delete_image_cache($context, $owner_id, $image);
            $this->API_get_image_info_v2($context, $owner_id, $image);
        }
    }

    protected function actionGet_editor() {
        try {
            $context = $this->GP->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $owner_id = $this->GP->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $image = $this->GP->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $callback = $this->GP->get_filtered('callback', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $context && $image && $owner_id && $callback ? false : \Errors\common_error::R("invalid request");
            \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
            $image_info = \ImageFly\ImageInfoManager::F()->get_image_info($context, $owner_id, $image);
            $image_info ? FALSE : \Errors\common_error::R("not found");
            $this->out->add('get_image_url', urlencode("/MediaAPI/ImageFly/source?context={$context}&owner_id={$owner_id}&image={$image}"));
            $host = "http" . (\Router\Request::F()->https ? "s" : "") . "://" . \Router\Request::F()->host;
            $this->out->add("post_image_url", urlencode("{$host}/MediaAPI/ImageFly/update_source?context={$context}&owner_id={$owner_id}&image={$image}"));
            $this->out->add("editor_lang", urlencode("{$host}/assets/flash/pixlr/ru.rs"));
            $this->out->add("image_title", urlencode($image_info->title));
            $this->out->add("image_callback", $callback);
            $this->render_view('pixlr', 'pixlr');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    protected function actionUpdate_source() {
        try {
            $context = $this->GP->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $owner_id = $this->GP->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $image = $this->GP->get_filtered('image', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $context && $image && $owner_id ? false : \Errors\common_error::R("invalid request");
            \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
            $files = \DataMap\FileMap::F();
            ($files->length < 1) ? \Errors\common_error::R("no files") : false;
            ($files->length > 1) ? \Errors\common_error::R("only one file allowed") : false;
            \ImageFly\ImageFly::F()->update_image_from_post($context, $owner_id, $image);
        } catch (\Exception $e) {
            if (!headers_sent()) {
                header("HTTP/1.0 500 Internal Server Error", true, 500);
            }
            die($e->getMessage());
        }
    }

    protected function API_reorder_images() {

        $data = $this->GP->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        $image_data = \DataMap\CommonDataMap::F()->rebind($data);
        $context = $image_data->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $owner_id = $image_data->get_filtered('id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $new_order = $image_data->get_filtered('order', ['ArrayOfNEString', 'NEArray', 'DefaultNull']);
        $context && $owner_id && $new_order ? false : \Errors\common_error::R("invalid request");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        \ImageFly\ImageInfoManager::F()->reorder_images($context, $owner_id, $new_order);
        $this->API_list($context, $owner_id);
    }

    protected function API_upload_color() {
        $color_uid = $this->GP->get_filtered('color_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $color_uid ? false : \Errors\common_error::R("no color name defined");
        $log = \ImageFly\ImageFly::F()->handle_upload_color($color_uid);
        $this->out->add('upload_log', $log);
        $this->out->add('upload_screen', ob_get_clean());
    }

    protected function API_upload_fallback() {
        $context = $this->GP->get_filtered("context", ["Strip", "Trim", "NEString", "DefaultNull"]);
        $context ? 0 : \ImageFly\ImageFlyError::R("context is required");
        \ImageFly\MediaContextInfo::F()->context_exists($context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $context);
        \ImageFly\ImageFly::F()->delete_image_cache("fallback", "1", $context);
        $log = \ImageFly\ImageFly::F()->handle_upload_manual("fallback", "1", $context, true);
        $this->out->add('upload_log', $log);
        $this->out->add('upload_screen', ob_get_clean());
    }

}
