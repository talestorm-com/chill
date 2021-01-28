<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of PublicGalleryController
 *
 * @author eve
 */
class PublicGalleryController extends AbstractFrontendController {

    //put your code here

    protected function check_api_access(): bool {
        return $this->auth->authenticated;
    }

    protected function check_access(): bool {
        return true;
    }

    protected function check_access_int() {
        if (!$this->auth->authenticated) {
            if (!headers_sent()) {
                header('HTTP/1.0 403 Forbidden');
            }
            die('access denied');
        }
        $this->auth->authenticated ? 0 : \Auth\AuthError::R(\Auth\AuthError::NOT_AUTHORIZED);
    }

    public function API_list_my_galleries() {
        \PublicMedia\UserPublicGalleryLister::F(\DataMap\InputDataMap::F(), $this->auth->id, $this->out)->run();
    }

    public function API_list_my_media() {
        \PublicMedia\UserPublicItemsLister::F(\DataMap\InputDataMap::F(), $this->auth->id, $this->out)->run();
    }

    public function API_post_gallery() {
        $datamap = \DataMap\InputDataMap::F();
        $w = \PublicMedia\Writer\Gallery\Writer::F($datamap, $datamap, \Auth\Auth::F()->get_id());
        $result_id = $w->run();

        $gallery = \PublicMedia\PublicMediaGallery::F()->load_by_id($result_id);

        $gallery->owner_id === $this->auth->id ? 0 : \Errors\common_error::R("access denied");
        $this->out->add('gallery', $gallery);
        $this->out->add("warnings", $w->messages);
    }

    public function API_get_gallery(int $rid = null) {
        $gallery_id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered("id", ['IntMore0']);
        \Filters\FilterManager::F()->raise_array_error(['id' => $gallery_id]);
        $gallery = \PublicMedia\PublicMediaGallery::F()->load_by_id($gallery_id);
        $gallery->owner_id === $this->auth->id ? 0 : \Errors\common_error::R("access denied");
        $this->out->add('gallery', $gallery);
    }

    public function API_remove_gallery() {
        $gallery_id = \DataMap\InputDataMap::F()->get_filtered("id", ['IntMore0']);
        \Filters\FilterManager::F()->raise_array_error(['id' => $gallery_id]);
        $gallery = \PublicMedia\PublicMediaGalleryShort::F()->load_by_id($gallery_id);
        if ($gallery && $gallery->valid) {
            if ($gallery->owner_id === $this->auth->id) {
                \PublicMedia\Writer\Gallery\GalleryRemover::F($gallery)->run();
            }
        }
        if (\DataMap\InputDataMap::F()->get_filtered("return_list", ["Boolean", "DefaultFalse"])) {
            $this->API_list_my_galleries();
        } elseif (\DataMap\InputDataMap::F()->get_filtered("return_items", ["Boolean", "DefaultFalse"])) {
            $this->API_list_my_media();
        }
    }

    public function API_get_item() {
        
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ['IntMore0']);
        \Filters\FilterManager::F()->raise_array_error(compact('id'));
        $item = \PublicMedia\PublicMediaItem::F()->load($id);
        $item && $item->valid ? 0 : \Errors\common_error::R("not found");
        $item->owner_id === $this->auth->get_id() ? 0 : \Errors\common_error::R("access denied");
        $this->out->add("media_item", $item);
    }

    public function API_get_item2() {
        return $this->API_get_item();        
    }

    public function API_put_item() {
        $writer = \PublicMedia\Writer\Item\Writer::F(\DataMap\InputDataMap::F(), \DataMap\InputDataMap::F(), $this->auth->get_id());
        $writer->run();
        $item = \PublicMedia\PublicMediaItem::F()->load($writer->result_id);
        $this->out->add('item', $item);
    }

    public function API_remove_item() {        
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ['IntMore0']);
        \Filters\FilterManager::F()->raise_array_error(compact('id'));
        $item = \PublicMedia\PublicMediaItemShort::F()->load($id);
        $item && $item->valid ? 0 : \Errors\common_error::R("not found");
        $item->owner_id === $this->auth->get_id() ? 0 : \Errors\common_error::R("access denied");
        \PublicMedia\Writer\Item\Remover::F($item)->run();
        $this->after_remove($item->gallery_id);
    }

    public function API_remove_item2() {
        return $this->API_remove_item();        
    }

    protected function after_remove(int $gallery_id = null) {
        if (\DataMap\InputDataMap::F()->get_filtered("return_gallery", ['Boolean', 'DefaultFalse'])) {
            if ($gallery_id) {
                $this->API_get_gallery($gallery_id);
            }
        }
        if (\DataMap\InputDataMap::F()->get_filtered("return_list", ['Boolean', 'DefaultFalse'])) {
            $this->API_list_my_media();
        }
    }

    public function actionget_public_image() {
        $this->check_access_int();
        $query = \Router\Request::F()->request_path;
        $m = [];

        if (preg_match('/\/{0,1}media\/public\/(?P<g>\d{1,})\/cover\.jpg/i', $query, $m)) {
            $this->send_gallery_cover(intval($m['g']));
        } elseif (preg_match("/\/{0,1}media\/public\/(?P<g>\d{1,})\/preview\/(?P<i>\d{1,})\.jpg/i", $query, $m)) {
            $this->send_image_preview(intval($m['g']), intval($m['i']));
        } else if (preg_match("/\/{0,1}media\/public\/(?P<g>\d{1,})\/(?P<i>\d{1,})/i", $query, $m)) {
            $this->send_media_object(intval($m['g']), intval($m['i']));
        }
        if (!headers_sent()) {
            header('HTTP/1.0 404 Not found');
        }
        die("not found");
    }

    protected function send_image_preview(int $gallery_id, int $image_id) {
        $item = \PublicMedia\PublicMediaItemShort::F()->load($image_id);
        if ($item && $item->valid && $item->owner_id === $this->auth->get_id() && $item->gallery_id===$gallery_id) {
            $path = $item->get_preview_path();
            if (file_exists($path)) {
                header("Content-Type: image/jpeg");
                header("X-SendFile: " . realpath($path));
                die();
            }
        }
        if (!headers_sent()) {
            header('HTTP/1.0 404 Not found');
        }
        die("not found");
    }

    protected function send_media_object(int $gallery_id, int $image_id) {
        $item = \PublicMedia\PublicMediaItemShort::F()->load($image_id);
        if ($item && $item->valid && $item->owner_id === $this->auth->get_id() && $item->gallery_id===$gallery_id) {
            $path = $item->get_media_path();
            if (file_exists($path)) {
                header("Content-Type: {$item->safe_type}");
                header("X-SendFile: " . realpath($path));
                die();
            }
        }
        if (!headers_sent()) {
            header('HTTP/1.0 404 Not found');
        }
        die("not found");
    }

    protected function send_gallery_cover(int $gallery_id) {
        $gallery = \PublicMedia\PublicMediaGalleryShort::F()->load_by_id($gallery_id);
        if ($gallery && $gallery->valid && $gallery->visible || $gallery->owner_id === \Auth\Auth::F()->get_id()) {
            $path = $gallery->get_files_path() . "cover.jpg";
            if (!file_exists($path)) {
                $path = \Config\Config::F()->WEB_ROOT . "fallback" . DIRECTORY_SEPARATOR . "gallery_cover.jpg";
            }
            if (file_exists($path)) {
                header("Content-Type: image/jpeg");
                header("X-SendFile: " . realpath($path));
                die();
            }
        }
        if (!headers_sent()) {
            header('HTTP/1.0 404 Not found');
        }
        die("not found");
    }

}
