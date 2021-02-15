<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of WorkshopController
 *
 * @author eve
 */
class WorkshopController extends AbstractFrontendController {

    /** @var int */
    protected $client_id;

    protected function check_api_access(): bool {
        $this->client_id = $this->auth->is_authentificated() ? $this->auth->id : null;
        return $this->auth->is_authentificated();
    }

    //<editor-fold defaultstate="collapsed" desc="collection manipulation">

    /**
     * Список коллекций пользователя
     */
    protected function API_get_collections() {
        \ProtectedMedia\ProtectedGalleryLister::F(\DataMap\InputDataMap::F(), $this->client_id)->get($this->out);
    }

    /**
     * полное описание коллеции с элементами
     * @param string $oid
     */
    protected function API_get_collection(string $oid = null) {
        $d = [
            "uid" => $oid ? $oid : \DataMap\InputDataMap::F()->get("uid", ["Strip", "Trim", "NEString", "DefaultNull"]),
            "owner_id" => $this->auth->is_authentificated() ? $this->auth->get_id() : null,
        ];
        $dc = \Filters\FilterManager::F()->apply_filter_array($d, [
            'uid' => ["NEString", "DefaultNull"],
            'owner_id' => ["IntMore0"]
        ]);
        \Filters\FilterManager::F()->raise_array_error($dc);
        $this->out->add('collection', \ProtectedMedia\ProtectedGallery::LOAD($dc["uid"], $dc["owner_id"]));
    }

    /**
     * СОздает или обновляет коллекцию
     */
    protected function API_put_collection() {
        $builder = \DB\SQLTools\SQLBuilder::F();
        $writer = \ProtectedMedia\ProtectedGalleryWriter::F();
        $writer->set_user_id($this->auth->id);
        $tv = "@a" . md5(__METHOD__);
        $writer->run(\DataMap\InputDataMap::F(), $builder, $tv);
        $result_uid = $builder->execute_transact_ret_str($tv);
        if (count(\DataMap\FileMap::F())) {
            $this->API_put_collection_cover($result_uid, $this->auth->id);
        }
        $this->API_get_collection($result_uid);
    }

    /**
     * установка "крышки" коллекции
     * @param string $ruid
     * @param int $rowner_id
     */
    protected function API_put_collection_cover(string $ruid = null, int $rowner_id = null) {
        $uid = $ruid ? $ruid : \DataMap\InputDataMap::F()->get_filtered('uid', ["Trim", "NEString", "DefaultNull"]);
        $owner_id = $rowner_id ? $rowner_id : $this->auth->id;
        $upload_log = [];
        if (count(\DataMap\FileMap::F())) {
            try {
                \ImageFly\ProtectedImageUploader::F()->upload_protected_gallery_cover($uid, $owner_id);
            } catch (\Throwable $e) {
                $upload_log[] = $e->getMessage();
            }
            $this->out->add("upload_log", ["log" => $upload_log]);
        }
    }

    /**
     * Удаление крышки коллекции
     */
    protected function API_remove_collection_cover() {
        $in = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), ["uid" => ["Trim", "NEString"]]);
        \Filters\FilterManager::F()->raise_array_error($in);
        $path = $this->get_collection_path($in['uid']) . "cover.jpg";
        if (file_exists($path) && is_file($path) && is_writable($path)) {
            @unlink($path);
        }
    }

    /**
     * удаление коллекции
     */
    protected function API_remove_collection() {
        $uid = \DataMap\InputDataMap::F()->get_filtered("uid", ["Strip", "Trim", "NEString"]);
        \Filters\FilterManager::F()->raise_array_error(['uid' => $uid]);
        $collection = \ProtectedMedia\ProtectedGallery::LOAD($uid, $this->auth->id);
        if ($collection && $collection->valid && $collection->owner_id === $this->auth->id) {
            $collection_path = $this->get_collection_path($collection->uid);
            $builder = \DB\SQLTools\SQLBuilder::F();
            $builder->push("DELETE FROM protected__gallery WHERE uid=:Puid AND owner_id=:Powner_id;");
            $builder->push_params([
                ":Puid" => $uid,
                ":Powner_id" => $this->auth->id,
            ]);
            $builder->execute_transact();
            \Helpers\Helpers::rm_dir_recursive($collection_path);
        } else {
            \Errors\common_error::R("not found");
        }
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="collection item routines">

    /**
     * полная инфа об элементе
     * @return type
     */
    protected function API_get_collection_item(string $ruid = null) {
        $uid = $ruid ? $ruid : \DataMap\InputDataMap::F()->get_filtered("uid", ["Strip", "Trim", "NEString"]);
        \Filters\FilterManager::F()->raise_array_error(['uid' => $uid]);
        $item = \ProtectedMedia\ProtectedGalleryItem::LOAD($uid, $this->auth->id);
        if ($item && $item->valid) {
            $this->out->add("item", $item);
            return;
        }
        \Errors\common_error::R("not found");
    }

    /**
     * СОздание и обновление элемента
     * @return type
     */
    protected function API_put_collection_item() {
        $uid = \DataMap\InputDataMap::F()->get_filtered("uid", ["Strip", "Trim", "NEString", "DefaultNull"]);
        $uid ? $this->update_collection_item() : $uid = $this->create_collection_item();
        $this->API_get_collection_item($uid);
    }

    //<editor-fold defaultstate="collapsed" desc="item related utils">

    /**
     * 
     * @return string
     * @throws \Throwable
     */
    protected function create_collection_item() {
        $in = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
            'gallery_uid' => ["Strip", "Trim", "NEString"],
            'title' => ["Trim", "NEString", "DefaultEmptyString"],
            'sort' => ["Int", "Default0"],
            'version' => ["Int", "Default0"],
            'info' => ["NEString", "DefaultEmptyString"],
            'preset' => ["NEString", "DefaultEmptyString"],
            'extension' => ["Trim", "NEString", "DefaultNull"],
            "mime" => ["Trim", "NEString", "DefaultNull"],
            "aspect" => ["Float", "DefaultNull"]
        ]);
        \Filters\FilterManager::F()->raise_array_error($in);
        if (count(\DataMap\FileMap::F()) !== 1) {
            \Errors\common_error::RF("%s accepts 1 file exactly, %d passed", __METHOD__, count(\DataMap\FileMap::F()));
        }
        $file = \DataMap\FileMap::F()->get_by_index();
        $aspect = 0;
        $type = $this->detect_media_type($file, $aspect);
        $type ? false : \Errors\common_error::R("unknown media type");
        if (!$in["extension"]) {
            $fil = \Helpers\Helpers::NEString(pathinfo($file->name, PATHINFO_EXTENSION), null);
            $in["extension"] = $fil;
        }
        $in["type"] = $type === "image" ? "image/jpeg" : ($in["mime"] ? $in["mime"] : $file->type);
        $in["aspect"] = $in['aspect'] === null ? $aspect : $in["aspect"];
        $type === "image" ? $in["extension"] = "jpg" : 0;
        $gallery_path = $this->get_collection_path($in["gallery_uid"]);
        if (!(file_exists($gallery_path) && is_readable($gallery_path) && is_dir($gallery_path) && is_writable($gallery_path))) {
            //\Errors\common_error::R("gallery path not found");
        }
        $uid = $this->create_file_record($in);
        // $this->out->add("xmime", $file->type);
        $aspect = 0;
        try {
            if ($type === "image") {
                $this->handle_upload_image($uid, $in["gallery_uid"], $file, $aspect);
            } else {
                $this->handle_upload_video($uid, $in["gallery_uid"], $file, $in["extension"]);
            }
        } catch (\Throwable $e) {
            $this->remove_file_record($uid);
            throw $e;
        }
        return $uid;
    }

    protected function remove_file_record(string $uid) {
        $builder = \DB\SQLTools\SQLBuilder::F();
        $builder->push("DELETE FROM protected__gallery__item WHERE uid=:Puid AND owner_id=:Powner_id;");
        $builder->push_params([
            ":Puid" => $uid,
            ":Powner_id" => $this->auth->get_id(),
        ]);
        $builder->execute_transact();
    }

    protected function create_file_record(array $in): string {
        $tn = "@a" . md5(__METHOD__);
        $builder = \DB\SQLTools\SQLBuilder::F();
        $builder->push("SET {$tn} = UUID();");
        $builder->push("            
            INSERT INTO protected__gallery__item(`uid`,`gallery_uid`,`owner_id`,`title`,`type`,`extension`,`sort`,`version`,`info`,`preset`,`aspect`)
            VALUES( {$tn},:Pgallery_uid,:Powner_id,:Ptitle,:Ptype,:Pextension,:Psort,:Pversion,:Pinfo,:Ppreset,:Paspect )
            ");
        $builder->push_params([
            ":Pgallery_uid" => $in["gallery_uid"],
            ":Powner_id" => $this->auth->id,
            ":Ptitle" => $in["title"],
            ":Ptype" => $in["type"],
            ":Pextension" => $in["extension"],
            ":Psort" => $in["sort"],
            ":Pversion" => $in["version"],
            ":Pinfo" => $in["info"],
            ":Ppreset" => $in["preset"],
            ":Paspect" => $in["aspect"]
        ]);
        //$this->out->add('dbg_sq', $builder->sql);
        return $builder->execute_transact_ret_str($tn);
    }

    //<editor-fold defaultstate="collapsed" desc="uploaders">    
    protected function handle_upload_image($uid, $gallery_uid, \DataMap\UploadedFile $file) {
        \ImageFly\ProtectedImageUploader::F()->upload_protected_gallery_image($gallery_uid, $uid, $this->auth->id, $file);
    }

    protected function handle_upload_video($uid, $gallery_uid, \DataMap\UploadedFile $file, $extension) {
        \ImageFly\ProtectedImageUploader::F()->upload_protected_gallery_video($gallery_uid, $uid, $this->auth->id, $file, $extension);
    }

    //</editor-fold>

    protected function update_collection_item() {
        $updater = \ProtectedMedia\ProtectedGalleryItemUpdater::F(\DataMap\InputDataMap::F(), $this->auth->id);
        $builder = \DB\SQLTools\SQLBuilder::F();
        $aspect_restore = false;
        $uid = $updater->run($builder, $aspect_restore);
        //$this->out->add("b", $builder->sql);
        $builder->empty ? 0 : $builder->execute_transact();
        if ($aspect_restore) {
            $this->restore_item_aspect($uid);
        }
    }

    protected function restore_item_aspect(string $uid) {
        $row = \DB\DB::F()->queryRow("SELECT uid,gallery_uid,extension FROM protected__gallery__item WHERE uid=:P", [":P" => $uid]);
        if ($row) {
            $ext = \Helpers\Helpers::NEString(trim($row['extension']), null);
            $ext = $ext ? ".{$ext}" : "";
            $item_path = $this->get_collection_path($row["gallery_uid"]) . $row["uid"] . $ext;
            // $this->out->add("ddddddd", $item_path);
            $aspect = null;
            if (file_exists($item_path) && is_readable($item_path) && is_file($item_path)) {
                try {
                    $this->detect_media_type_path($item_path, $aspect);
                } catch (\Throwable $e) {
                    $aspect = null;
                }
            }
            if ($aspect !== null) {
                try {
                    \DB\SQLTools\SQLBuilder::F()->push("UPDATE protected__gallery__item SET aspect=:P WHERE uid=:PP;")
                            ->push_params([":P" => $aspect, ":PP" => $row["uid"]])->execute();
                } catch (\Throwable $e) {
                    
                }
            }
        }
    }

    //</editor-fold>

    /**
     *  Обновление превиева
     * 
     */
    protected function API_update_item_preview() {
        $uid = \DataMap\InputDataMap::F()->get_filtered("uid", ["Strip", "Trim", "NEString"]);
        \Filters\FilterManager::F()->raise_array_error(['uid' => $uid]);
        $gallery_item = \ProtectedMedia\ProtectedGalleryItem::LOAD($uid, $this->auth->id);
        if ($gallery_item && $gallery_item->valid && $gallery_item->owner_id === $this->auth->id) {
            if (count(\DataMap\FileMap::F())) {
                \ImageFly\ProtectedImageUploader::F()->upload_preview($gallery_item->gallery_uid, $gallery_item->uid, $this->auth->id, \DataMap\FileMap::F()->get_by_index());
            }
            if (\DataMap\InputDataMap::F()->exists("aspect")) {
                $aspect = \DataMap\InputDataMap::F()->get_filtered("aspect", ["Float", "DefaultNull"]);
                if ($aspect !== null) {
                    $builder = \DB\SQLTools\SQLBuilder::F()->push("UPDATE protected__gallery__item SET aspect=:P WHERE gallery_uid=:Pg AND uid=:Pu;");
                    $builder->push_params([":Pg" => $gallery_item->gallery_uid, ":Pu" => $gallery_item->uid]);
                    $aaspect = null;
                    if ($aspect < 0) {
                        $file_name = $this->get_collection_path($gallery_item->gallery_uid) . "preview.{$gallery_item->uid}.jpg";
                        if (file_exists($file_name)) {
                            $xaspect = null;
                            $this->detect_media_type_path($file_name, $xaspect);
                            if ($xaspect !== null && $xaspect > 0) {
                                $aaspect = $xaspect;
                            }
                        }
                    } else {
                        $aaspect = $aspect;
                    }
                    if ($aaspect !== null) {
                        $builder->push_param(":P", $aaspect);
                        $builder->execute_transact();
                    }
                }
            }
        }
    }

//</editor-fold>

    /**
     * Удаление элемента коллекции
     */
    protected function API_remove_collection_item() {
        $uid = \DataMap\InputDataMap::F()->get_filtered("uid", ["Strip", "Trim", "NEString"]);
        \Filters\FilterManager::F()->raise_array_error(['uid' => $uid]);
        $item = \ProtectedMedia\ProtectedGalleryItem::LOAD($uid, $this->auth->id);
        if ($item && $item->valid && $item->owner_id === $this->auth->id) {
            $gallery_path = $this->get_collection_path($item->gallery_uid);
            if (file_exists($gallery_path) && is_dir($gallery_path) && is_writable($gallery_path)) {
                \Helpers\Helpers::rm_files_by_regex($gallery_path, [
                    "/^{$item->uid}/i",
                    "/^preview\.{$item->uid}/i",
                ]);
            }
            $this->remove_file_record($uid);
        } else {
            \Errors\common_error::R("not found");
        }
        if (\DataMap\InputDataMap::F()->get_filtered("return_collection", ["Boolean", "DefaultFalse"])) {
            $this->API_get_collection($item->gallery_uid);
        }
    }

//<editor-fold defaultstate="collapsed" desc="image loader">

    public function actionGet_protected_image() {
        if (!$this->auth->is_authentificated()) {
            header('HTTP/1.0 403 Forbidden');
            die('private area');
        }
        $request = \Router\Request::F()->request_path;
        $m = [];
///media/private/7e0edde0-e863-11e9-8282-001e5826d92c/cover.jpg
        if (preg_match("/^\/{0,1}media\/private\/(?P<gallery>[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})\/(?P<name>[^\/]{1,})\.jpg$/i", $request, $m)) {
            $image_path = $this->get_collection_path($m["gallery"]) . $m["name"] . ".jpg";
            if (file_exists($image_path)) {
                header("Content-Type: image/jpeg");
                header("X-SendFile: " . realpath($image_path));
                die();
            }
// video file
        } else if (preg_match("/^\/{0,1}media\/private\/(?P<gallery>[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})\/(?P<name>[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})(\.(?P<ext>.{1,})){0,1}$/i", $request, $m)) {
            $item = \ProtectedMedia\ProtectedGalleryItem::LOAD($m["name"], $this->auth->id);
            if ($item && $item->valid && $item->gallery_uid === $m["gallery"] && $item->owner_id === $this->auth->id) {
                $path = $this->get_collection_path($item->gallery_uid) . "{$item->uid}{$item->dotted_extension}";
                if (file_exists($path) && is_readable($path) && is_file($path)) {
                    header("Content-Type: {$item->safe_type}");
                    header("X-SendFile: " . realpath($path));
                    die();
                }
            }
        } else if (preg_match("/^\/{0,1}media\/private\/(?P<gallery>[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})\/preview\/(?P<name>.{1,})\.jpg$/i", $request, $m)) {
            $target_file_path = $this->get_collection_path($m["gallery"]) . "preview.{$m["name"]}.jpg";
            if (!(file_exists($target_file_path) && is_file($target_file_path) && is_readable($target_file_path))) {
                $item = \ProtectedMedia\ProtectedGalleryItem::LOAD($m["uid"], $this->auth->id);
                if ($item && $item->valid && $item->gallery_uid === $m["gallery"] && $item->owner_id === $this->auth->id) {
                    if ($item->is_image) {
                        $target_path = $this->get_collection_path($item->gallery_uid) . "preview.{$item->uid}.jpg";
                        $source_path = $this->get_collection_path($item->gallery_uid) . "{$item->uid}.jpg";
                        \ImageFly\ProtectedImageUploader::F()->create_preview_for_image($target_path, $source_path);
                    } else {
                        $target_path = $this->get_collection_path($item->gallery_uid) . "preview.{$item->uid}.jpg";
                        $source_path = $this->get_collection_path($item->gallery_uid) . "{$item->uid}{$item->dotted_extension}";
                        \ImageFly\ProtectedImageUploader::F()->create_preview_for_video($target_path, $source_path);
                    }
                }
            }
            if ((file_exists($target_file_path) && is_file($target_file_path) && is_readable($target_file_path))) {
                header("Content-Type: image/jpeg");
                header("X-SendFile: " . realpath($target_file_path));
                die();
            }
        }
        header('HTTP/1.0 404 Not found');
        die('not found');
    }

//</editor-fold>   
//<editor-fold defaultstate="collapsed" desc="utilitary">
    protected function get_collection_path(string $uid): string {
        return \Config\Config::F()->PROTECTED_STORAGE_BASE . $this->auth->id . DIRECTORY_SEPARATOR . $uid . DIRECTORY_SEPARATOR;
    }

    protected function detect_media_type_path(string $path, &$aspect = 0) {
        $probe = null;
        $is_image = null;
        $is_video = null;
        $width = null;
        $height = null;
        try {
            $probe = new \ImageFly\FFProbe($path);
            $streams = array_key_exists("streams", $probe->metadata) ? $probe->metadata["streams"] : [];
            foreach ($streams as $stream) {
                if (is_array($stream) && array_key_exists("codec_tag", $stream)) {
                    if (\Helpers\Helpers::NEString($stream["codec_tag"], null) && $stream["codec_tag"] !== "0x00000000" && $stream["codec_tag"] !== "0x0000") {
                        $is_video = true;
                    }
                }
                if (is_array($stream) && array_key_exists("width", $stream) && floatval($stream["width"]) > 0 && $width === null) {
                    $width = floatval($stream["width"]);
                }
                if (is_array($stream) && array_key_exists("height", $stream) && floatval($stream["height"]) > 0 && $height === null) {
                    $height = floatval($stream["height"]);
                }
            }
        } catch (\Throwable $e) {
            $probe = null;
            //throw $e;
        }
        //$this->out->add("probe", $probe ? $probe->metadata : null);
        if (!$is_video) {
            $imgk = null;
            try {
                $imgk = new \Imagick($path);
                // $this->out->add("mime", $imgk->getImageMimeType());
                $width = $imgk->getImageWidth();
                $height = $imgk->getImageHeight();
                $is_image = true;
            } catch (\Throwable $e) {
                $imgk = null;
                $is_image = false;
            }
            if ($is_image && !$is_video) {
                
            } else if ($is_video && !$is_image) {
                
            }
        }
        // $this->out->add("det", compact('is_image', 'is_video'));
        $aspect = $width !== null && $height !== null && $width > 0 && $height > 0 ? ($width / $height) : 0;
        return $is_image && !$is_video ? "image" : ($is_video && !$is_image ? "video" : null);
    }

    protected function detect_media_type(\DataMap\UploadedFile $file, &$aspect = 0) {
        return $this->detect_media_type_path($file->tmp_name, $aspect);
        $probe = null;
        $is_image = null;
        $is_video = null;
        $width = null;
        $height = null;
        try {
            $probe = new \ImageFly\FFProbe($file->tmp_name);
            $streams = array_key_exists("streams", $probe->metadata) ? $probe->metadata["streams"] : [];
            foreach ($streams as $stream) {
                if (is_array($stream) && array_key_exists("codec_tag", $stream)) {
                    if (\Helpers\Helpers::NEString($stream["codec_tag"], null) && $stream["codec_tag"] !== "0x00000000" && $stream["codec_tag"] !== "0x0000") {
                        $is_video = true;
                    }
                }
                if (is_array($stream) && array_key_exists("width", $stream) && floatval($stream["width"]) > 0 && $width === null) {
                    $width = floatval($stream["width"]);
                }
                if (is_array($stream) && array_key_exists("height", $stream) && floatval($stream["height"]) > 0 && $height === null) {
                    $height = floatval($stream["height"]);
                }
            }
        } catch (\Throwable $e) {
            $probe = null;
//throw $e;
        }
//$this->out->add("probe", $probe ? $probe->metadata : null);
        $imgk = null;
        try {
            $imgk = new \Imagick($file->tmp_name);
//$this->out->add("mime", $imgk->getImageMimeType());
            $width = $imgk->getImageWidth();
            $height = $imgk->getImageHeight();
            $is_image = true;
        } catch (\Throwable $e) {
            $imgk = null;
            $is_image = false;
        }
        if ($is_image && !$is_video) {
            
        } else if ($is_video && !$is_image) {
            
        }
        //  $this->out->add("det", compact('is_image', 'is_video'));
        $aspect = $width !== null && $height !== null && $width > 0 && $height > 0 ? ($width / $height) : 0;
        return $is_image && !$is_video ? "image" : ($is_video && !$is_image ? "video" : null);
    }

//</editor-fold>
}
