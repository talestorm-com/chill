<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * @property string $image_storage хранилище оригиналов (базовый каталог - далее по контекстам и id)
 * @property string $image_base_web верхняя папка для веба (кеш подрезков)
 * @property string $base_url  базовый урл для редиректов
 */
class ImageFly {

    use \common_accessors\TCommonAccess;

    const JPEG_COMPRESS_MODE = \Imagick::COMPRESSION_LOSSLESSJPEG;
    const JPEG_COMPRESS_LEVEL = 85;

    /** @var ImageFly */
    protected static $instance = null;

    /** @var string */
    protected $image_storage;

    /** @var string */
    protected $image_base_web;

    /** @var string */
    protected $base_url;

    //<editor-fold defaultstate="collapsed" desc="getters">
    protected function __get__image_storage() {
        return $this->image_storage;
    }

    protected function __get__image_base_web() {
        return $this->image_base_web;
    }

    protected function __get__base_url() {
        return $this->base_url;
    }

    //</editor-fold>

    protected function __construct() {
        $this->image_storage = \Config\Config::F()->IMAGE_STORAGE_PATH;
        $this->image_base_web = \Config\Config::F()->IMAGE_WEB_BASE_PATH;
        $this->base_url = \Config\Config::F()->IMAGE_WEB_BASE_URL;
        static::$instance = $this;
    }

    /**
     * 
     * @return \ImageFly\ImageFly
     */
    public static function F(): ImageFly {
        return static::$instance ? static::$instance : new static();
    }

    public function handle_upload_color(string $color_uid) {
        $uploaded = \DataMap\FileMap::F();
        $log = [];
        $c = 0;
        foreach ($uploaded as $uploaded_file /* @var $uploaded_file \DataMap\UploadedFile */) {
            try {
                $this->upload_one_color($color_uid, $uploaded_file);
                break;
            } catch (\Exception $e) {
                $log[] = ["t" => "error on uploading `%s`:{$e->getMessage()}", 'n' => $uploaded_file->name];
                break;
            }
        }
        return $log;
    }

    public function handle_upload_manual(string $context, string $owner_id, string $name = null, bool $or_update = false) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $uploaded = \DataMap\FileMap::F();
        if (count($uploaded) !== 1) {
            ImageFlyError::RF("`%s` requires one file!", __METHOD__);
        }

        $log = [];
        $c = 0;
        foreach ($uploaded as $uploaded_file /* @var $uploaded_file \DataMap\UploadedFile */) {
            if ($uploaded_file instanceof \DataMap\FakeUploadedFile) {
                $uploaded_file->set_content_type("image/jpeg");
            }
            try {
                $this->upload_one_file($context, $owner_id, $uploaded_file, $name, $or_update);
                $c++;
            } catch (\Exception $e) {
                $log[] = ["t" => "error on uploading `%s`:{$e->getMessage()}", 'n' => $uploaded_file->name];
            }
        }
        return $log;
    }

    public function handle_upload() {
        $context = \DataMap\InputDataMap::F()->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $context ? false : ImageFlyError::R("no context defined");

        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $uploaded = \DataMap\FileMap::F();

        $id = \DataMap\InputDataMap::F()->get_filtered('owner_id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $id ? false : ImageFlyError::R("no media owner id provided in upload operation");
        echo $id;
        //var_dump($_FILES);
        $log = [];
        $c = 0;
        foreach ($uploaded as $uploaded_file /* @var $uploaded_file \DataMap\UploadedFile */) {
            try {
                $this->upload_one_file($context, $id, $uploaded_file);
                $c++;
            } catch (\Exception $e) {
                $log[] = ["t" => "error on uploading `%s`:{$e->getMessage()}", 'n' => $uploaded_file->name];
            }
        }
        return $log;
    }

    public function add_image_from_file(string $filename, string $context, string $owner_id, $prepend = false) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $target_path = $this->image_storage . $context . DIRECTORY_SEPARATOR . $owner_id . DIRECTORY_SEPARATOR;
        $target_name_md = md5(implode("|", [$filename, $context, time(), filemtime($filename)]));
        $target_name = "{$target_name_md}.jpg";
        $target_name_old = "{$target_name_md}.png";
        // echo $target_name;
        if (!(file_exists($target_path) && is_dir($target_path))) {
            mkdir($target_path, 0777, true);
        }
        if (file_exists("{$target_path}{$target_name}") && is_file("{$target_path}{$target_name}")) {
            @unlink("{$target_path}{$target_name}");
        }
        if (file_exists("{$target_path}{$target_name_old}") && is_file("{$target_path}{$target_name_old}")) {
            @unlink("{$target_path}{$target_name_old}");
        }
        $image = new \Imagick($filename);
        $image->setimageformat('jpg');
        $image->setimagecompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setimagecompressionquality(95);
        $image->setcompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setcompressionquality(95);
        $image->writeimage("{$target_path}{$target_name}");
        $image->destroy();
        $image = null;
        //copy($filename, "{$target_path}{$target_name}");
        ImageInfoManager::F()->register_image_info($context, $owner_id, $target_name_md);
        if ($prepend) {
            ImageInfoManager::F()->set_image_order_first($context, $owner_id, $target_name_md);
        }
        $this->delete_image_cache($context, $owner_id, $target_name_md);
        return $target_name_md;
    }

    protected function process_upload_manual_context_smile(string $context, string $owner_id, string $upload_id, \DataMap\UploadedFile $file) {
        MediaContextInfo::F()->can_process_mime($context, $file->type) ? false : ImageFlyError::RF("mime not supported for this context");
        $target_path = $this->image_storage . $context . DIRECTORY_SEPARATOR . $owner_id . DIRECTORY_SEPARATOR;
        $target_name_md = $upload_id ? $upload_id : md5(implode("|", [$file->name, $context, time(), $file->tmp_name]));
        $target_name = "{$target_name_md}.png";
        if (!(file_exists($target_path) && is_dir($target_path))) {
            mkdir($target_path, 0777, true);
        }
        $image = new \Imagick($file->tmp_name);
        $source_width = $image->getimagewidth();
        $source_height = $image->getimageheight();
        if ($source_height < MediaContextInfo::F()->get_context_min_height($context) || $source_width < MediaContextInfo::F()->get_context_min_width($context)) {
            ImageFlyError::R("image too small to upload in this context");
        }
        if ($source_height > MediaContextInfo::F()->get_context_max_height($context) || $source_width > MediaContextInfo::F()->get_context_max_width($context)) {
            $image = $this->resize_image_to_max_dimensions($image, $context);
        }
        if (file_exists("{$target_path}{$target_name}") && is_file("{$target_path}{$target_name}")) {
            @unlink("{$target_path}{$target_name}");
        }
        $image->setimageformat('png');
        $image->setOption('png:format', "png32");
        //$image->setimagecompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        // $image->setimagecompressionquality(95);
        // $image->setcompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        // $image->setcompressionquality(95);
        $image->writeimage("{$target_path}{$target_name}");
        ImageInfoManager::F()->register_or_update_image_info($context, $owner_id, $target_name_md);
        $this->delete_image_cache($context, $owner_id, $target_name_md);
    }

    public function process_upload_manual(string $context, string $owner_id, string $upload_id, \DataMap\UploadedFile $file) {
        $fn = sprintf("process_upload_manual_context_%s", mb_strtolower($context, 'UTF-8'));
        if (method_exists($this, $fn)) {
            return $this->$fn($context, $owner_id, $upload_id, $file);
        }
        MediaContextInfo::F()->can_process_mime($context, $file->type) ? false : ImageFlyError::RF("mime %s not supported for context %s",$file->type,$context);
        $target_path = $this->image_storage . $context . DIRECTORY_SEPARATOR . $owner_id . DIRECTORY_SEPARATOR;
        $target_name_md = $upload_id ? $upload_id : md5(implode("|", [$file->name, $context, time(), $file->tmp_name]));
        $target_name = "{$target_name_md}.jpg";
        $target_name_old = "{$target_name_md}.png";
        // echo $target_name;
        if (!(file_exists($target_path) && is_dir($target_path))) {
            mkdir($target_path, 0777, true);
        }
        $image = new \Imagick($file->tmp_name);
        $source_width = $image->getimagewidth();
        $source_height = $image->getimageheight();
        if ($source_height < MediaContextInfo::F()->get_context_min_height($context) || $source_width < MediaContextInfo::F()->get_context_min_width($context)) {
            ImageFlyError::R("image too small to upload in this context");
        }
        if ($source_height > MediaContextInfo::F()->get_context_max_height($context) || $source_width > MediaContextInfo::F()->get_context_max_width($context)) {
            $image = $this->resize_image_to_max_dimensions($image, $context);
        }
        if (file_exists("{$target_path}{$target_name}") && is_file("{$target_path}{$target_name}")) {
            @unlink("{$target_path}{$target_name}");
        }
        if (file_exists("{$target_path}{$target_name_old}") && is_file("{$target_path}{$target_name_old}")) {
            @unlink("{$target_path}{$target_name_old}");
        }
        $image->setimageformat('jpg');
        $image->setimagecompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setimagecompressionquality(95);
        $image->setcompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setcompressionquality(95);
        $image->writeimage("{$target_path}{$target_name}");
        ImageInfoManager::F()->register_or_update_image_info($context, $owner_id, $target_name_md);
        $this->delete_image_cache($context, $owner_id, $target_name_md);
    }

    protected function upload_one_color(string $color_uid, \DataMap\UploadedFile $file) {
        $target_path = $this->image_storage . '_color' . DIRECTORY_SEPARATOR;
        $target_name_md = "{$color_uid}"; //md5(implode("|", [$file->name, $context, time(), $file->tmp_name]));
        $target_name = "{$target_name_md}.jpg";
        $target_name_old = "{$target_name_md}.png";
        if (!(file_exists($target_path) && is_dir($target_path))) {
            mkdir($target_path, 0777, true);
        }
        $image = new \Imagick($file->tmp_name);
        $source_width = $image->getimagewidth();
        $source_height = $image->getimageheight();
        if ($source_height < MediaContextInfo::F()->get_context_min_height("_color") || $source_width < MediaContextInfo::F()->get_context_min_width("_color")) {
            ImageFlyError::R("image too small to upload in this context");
        }
        if ($source_height > MediaContextInfo::F()->get_context_max_height("_color") || $source_width > MediaContextInfo::F()->get_context_max_width("_color")) {
            $image = $this->resize_image_to_max_dimensions($image, "_color");
        }


        $image->setimageformat('jpg');
        $image->setimagecompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setimagecompressionquality(95);
        $image->setcompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setcompressionquality(95);
        if (file_exists("{$target_path}{$target_name}") && is_file("{$target_path}{$target_name}")) {
            @unlink("{$target_path}{$target_name}");
        }
        if (file_exists("{$target_path}{$target_name_old}") && is_file("{$target_path}{$target_name_old}")) {
            @unlink("{$target_path}{$target_name_old}");
        }
        $image->writeimage("{$target_path}{$target_name}");
        ImageInfoManager::F()->register_color_info($target_name_md);
        $this->delete_color_cache($target_name_md);
    }

    protected function upload_one_file(string $context, string $id, \DataMap\UploadedFile $file, $name = null, bool $or_opdate = false) {
        MediaContextInfo::F()->can_process_mime($context, $file->type) ? false : ImageFlyError::RF("mime %s not supported for  context %s", $file->type, $context);
        $target_path = $this->image_storage . $context . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
        $target_name_md = $name ? $name : md5(implode("|", [$file->name, $context, time(), $file->tmp_name]));
        $target_name = "{$target_name_md}.jpg";
        $target_name_old = "{$target_name_md}.png";
        if ($file->type === 'image/gif') {
            $target_name = "{$target_name_md}.gif";
        }
        // echo $target_name;
        if (!(file_exists($target_path) && is_dir($target_path))) {
            mkdir($target_path, 0777, true);
        }
        if ($file->type === 'image/gif') {// для гифок - ну его нахер, обойдуться без обработки
            if (file_exists("{$target_path}{$target_name}") && is_file("{$target_path}{$target_name}")) {
                @unlink("{$target_path}{$target_name}");
            }
            $file->move("{$target_path}{$target_name}");
        } else {
            $image = new \Imagick($file->tmp_name);
            $source_width = $image->getimagewidth();
            $source_height = $image->getimageheight();
            if ($source_height < MediaContextInfo::F()->get_context_min_height($context) || $source_width < MediaContextInfo::F()->get_context_min_width($context)) {
                ImageFlyError::R("image too small to upload in this context");
            }
            if ($source_height > MediaContextInfo::F()->get_context_max_height($context) || $source_width > MediaContextInfo::F()->get_context_max_width($context)) {
                $image = $this->resize_image_to_max_dimensions($image, $context);
            }
            if (file_exists("{$target_path}{$target_name}") && is_file("{$target_path}{$target_name}")) {
                @unlink("{$target_path}{$target_name}");
            }
            if (file_exists("{$target_path}{$target_name_old}") && is_file("{$target_path}{$target_name_old}")) {
                @unlink("{$target_path}{$target_name_old}");
            }

            $image->setimageformat('jpg');
            $image->setimagecompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $image->setimagecompressionquality(95);
            $image->setcompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $image->setcompressionquality(95);
            $image->writeimage("{$target_path}{$target_name}");
        }
        if ($or_opdate) {
            ImageInfoManager::F()->register_or_update_image_info($context, $id, $target_name_md);
        } else {
            ImageInfoManager::F()->register_image_info($context, $id, $target_name_md);
        }
    }

    public function update_color_from_post(string $image_name) {
        $context = "_color";
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $file = \DataMap\FileMap::F()->get_by_index();
        $file ? false : ImageFlyError::R("no file available");
        // на mime - не обращаем внимания, octet-stream
        $target_path = $this->image_storage . $context . DIRECTORY_SEPARATOR;
        $target_name_md = $image_name;
        $target_name = "{$target_name_md}.jpg";
        $target_name_old = "{$target_name_md}.png";
        if (!(file_exists($target_path) && is_dir($target_path))) {
            mkdir($target_path, 0777, true);
        }
        $image = new \Imagick($file->tmp_name);
        $source_width = $image->getimagewidth();
        $source_height = $image->getimageheight();
        if ($source_height < MediaContextInfo::F()->get_context_min_height($context) || $source_width < MediaContextInfo::F()->get_context_min_width($context)) {
            ImageFlyError::R("image too small to upload in this context");
        }
        if ($source_height > MediaContextInfo::F()->get_context_max_height($context) || $source_width > MediaContextInfo::F()->get_context_max_width($context)) {
            $image = $this->resize_image_to_max_dimensions($image, $context);
        }
        $image->setimageformat('jpg');
        $image->setimagecompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setimagecompressionquality(95);
        $image->setcompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setcompressionquality(95);
        if (file_exists("{$target_path}{$target_name}") && is_file("{$target_path}{$target_name}") && is_readable("{$target_path}{$target_name}")) {
            @unlink("{$target_path}{$target_name}");
        }
        if (file_exists("{$target_path}{$target_name_old}") && is_file("{$target_path}{$target_name_old}") && is_readable("{$target_path}{$target_name_old}")) {
            @unlink("{$target_path}{$target_name_old}");
        }
        if (file_exists("{$target_path}{$target_name}") && is_file("{$target_path}{$target_name}")) {
            ImageFlyError::R("cant override:no access");
        }

        $image->writeimage("{$target_path}{$target_name}");
        $this->delete_color_cache($image_name);
    }

    public function update_image_from_post(string $context, string $owner_id, string $image_name) {
        if ($context === "_color") {
            return $this->update_color_from_post($image_name);
        }
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $file = \DataMap\FileMap::F()->get_by_index();
        $file ? false : ImageFlyError::R("no file available");
        // на mime - не обращаем внимания, octet-stream
        $target_path = $this->image_storage . $context . DIRECTORY_SEPARATOR . $owner_id . DIRECTORY_SEPARATOR;
        $target_name_md = $image_name;
        $target_name = "{$target_name_md}.jpg";
        $target_name_old = "{$target_name_md}.png";
        if (!(file_exists($target_path) && is_dir($target_path))) {
            mkdir($target_path, 0777, true);
        }
        $image = new \Imagick($file->tmp_name);
        $source_width = $image->getimagewidth();
        $source_height = $image->getimageheight();
        if ($source_height < MediaContextInfo::F()->get_context_min_height($context) || $source_width < MediaContextInfo::F()->get_context_min_width($context)) {
            ImageFlyError::R("image too small to upload in this context");
        }
        if ($source_height > MediaContextInfo::F()->get_context_max_height($context) || $source_width > MediaContextInfo::F()->get_context_max_width($context)) {
            $image = $this->resize_image_to_max_dimensions($image, $context);
        }
        $image->setimageformat('jpg');
        $image->setimagecompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setimagecompressionquality(95);
        $image->setcompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $image->setcompressionquality(95);
        if (file_exists("{$target_path}{$target_name}") && is_file("{$target_path}{$target_name}") && is_readable("{$target_path}{$target_name}")) {
            @unlink("{$target_path}{$target_name}");
        }
        if (file_exists("{$target_path}{$target_name_old}") && is_file("{$target_path}{$target_name_old}") && is_readable("{$target_path}{$target_name_old}")) {
            @unlink("{$target_path}{$target_name_old}");
        }
        if (file_exists("{$target_path}{$target_name}") && is_file("{$target_path}{$target_name}")) {
            ImageFlyError::R("cant override:no access");
        }
        $image->writeimage("{$target_path}{$target_name}");
        $this->delete_image_cache($context, $owner_id, $image_name);
    }

    protected function resize_image_to_max_dimensions(\Imagick $image, string $context): \Imagick {
        $max_width = MediaContextInfo::F()->get_context_max_width($context);
        $max_height = MediaContextInfo::F()->get_context_max_height($context);
        $min_height = MediaContextInfo::F()->get_context_min_height($context);
        $min_width = MediaContextInfo::F()->get_context_min_width($context);
        $current_width = $image->getimagewidth();
        $current_height = $image->getimageheight();
        $k = 1.0;
        if ($max_width < $current_width) {
            $k = $max_width / $current_width;
        }
        if ($max_height < $current_height) {
            $k = min([$k, $max_height / $current_height]);
        }
        $target_width = (int) round($current_width * $k, 0);
        $target_height = (int) round($current_height * $k, 0);
        if ($target_height < $min_height || $target_width < $min_width) {
            ImageFlyError::R("this image dimensions can not be transformed to context requirements");
        }
        $newimage = new \Imagick();
        $newimage->newimage($target_width, $target_height, new \ImagickPixel('rgba(0,0,0,0)'));
        $image->resizeimage($target_width, $target_height, \Imagick::FILTER_LANCZOS, .99);
        $newimage->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, 0, 0);
        return $newimage;
    }

    public function get_image_request(ImageFlyRequest $r) {
        return $this->get_image($r->context, $r->id, $r->image, $r->width, $r->height, $r->use_crop, $r->crop_fill, $r->background, $r->image_spec, $r->mime, $r->preset);
    }

    protected function resize_image_to_required_dimensions(\Imagick $image, int $width, int $height, bool $crop_fill, string $background) {
        // сводим в размер по условиям - нужно привести высоту и ширину отдельно?
        if ($width === 0 && $height === 0) {// если размеры не указаны вообще
            $target_width = $image->getimagewidth();
            $target_height = $image->getimageheight();
            // просто копируем исходник в результат
            $result_image = new \Imagick();
            $result_image->newimage($target_width, $target_height, new \ImagickPixel($background));
            $result_image->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, 0, 0);
            return $result_image;
        } else if ($width === 0) {
            // ширина равна 0 - ориентируемся по высоте
            $k = $height / $image->getimageheight();
            $width = (int) Round($image->getimagewidth() * $k, 0);
            $image->resizeimage($width, $height, \Imagick::FILTER_LANCZOS, .99);
            $result_image = new \Imagick();
            $result_image->newimage($width, $height, new \ImagickPixel($background));
            $result_image->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, 0, 0);
            return $result_image;
        } else if ($height === 0) {
            //hight is 0 - calculate by targer width
            $k = $width / $image->getimagewidth();
            $height = (int) round($image->getimageheight() * $k, 0);
            $image->resizeimage($width, $height, \Imagick::FILTER_LANCZOS, .99);
            $result_image = new \Imagick();
            $result_image->newimage($width, $height, new \ImagickPixel($background));
            $result_image->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, 0, 0);
            return $result_image;
        } else {
            // заданы оба значения - приводим наибольшее к размеру, остальное закрываем полями
            $ky = $height / $image->getimageheight();
            $kx = $width / $image->getimagewidth();
            ///если приводить сначала по размеру - можно попасть на некорректное приведение без зума по короткой стороне
            $target_k = $crop_fill ? max([$kx, $ky]) : min([$kx, $ky]); // если не нужно заполнение - берем минимальный коэффицент - тогда второая не выйдет запределы            
            $resize_width = (int) round($image->getimagewidth() * $target_k, 0);
            $resize_height = (int) round($image->getimageheight() * $target_k, 0);
            $image->resizeimage($resize_width, $resize_height, \Imagick::FILTER_LANCZOS, 0.99);
            $result_image = new \Imagick();
            $result_image->newimage($width, $height, new \ImagickPixel($background));
            $result_image->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, (int) round(($width - $resize_width) / 2, 0), (int) round(($height - $resize_height) / 2, 0));
            return $result_image;
        }
    }

    /**
     * 
     * @param string $context
     * @param string $id
     * @param string $image_name
     * @param int $width
     * @param int $height
     * @param bool $use_crop
     * @param string $background
     * @param type $related_image_spec
     * @return \Imagick
     */
    protected function get_image(string $context, string $id, string $image_name, int $width = 0, int $height = 0, bool $use_crop = true, bool $crop_fill = false, string $background = null, $related_image_spec = null, string $mime = "jpg", string $preset = null) {
//        if ($context === IMediaContext::_COLOR) {
//            return $this->get_color($image_name, $width, $height, $use_crop, $crop_fill, $background, $related_image_spec, $mime);
//        }
        $source_is_gif = false;
        if ($context === 'emojirenderer') {
            return $this->get_emoji($id, $width, $height, $use_crop, $crop_fill, $background, $related_image_spec, $mime);
        }
        $fn = sprintf("get_image_context_%s", mb_strtolower($context, 'UTF-8'));
        if (method_exists($this, $fn)) {
            return $this->$fn($context, $id, $image_name, $width, $height, $use_crop, $crop_fill, $background, $related_image_spec, $mime, $preset);
        }
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $image_spec = implode("", [
            "S",
            $width ? "W_{$width}" : "",
            $height ? "H_{$height}" : "",
            !$use_crop ? "C_0" : "",
            $crop_fill ? "CF_1" : "",
            $background ? "B_{$background}" : null,
            $preset && $preset !== "none" ? "PR_{$preset}" : "",
        ]);
        $relative_path = "{$context}/{$id}/";
        $relative_name_we = "{$relative_path}{$image_name}.{$image_spec}";
        $relative_name = "{$relative_name_we}.{$mime}";
        if ($related_image_spec && ($related_image_spec !== $image_spec)) {
            $redir_url = "{$this->base_url}{$relative_name}";
            MailformedImageSpec::R($redir_url);
        }
        $cached_name = "{$this->image_base_web}{$relative_name}";
        $cached_path = "{$this->image_base_web}{$relative_path}";
        $source_name = "{$this->image_storage}{$context}/{$id}/{$image_name}.jpg";
        if (!file_exists($source_name)) {
            $source_name = "{$this->image_storage}{$context}/{$id}/{$image_name}.png";
        }
        if (!file_exists($source_name)) {
            $source_name = "{$this->image_storage}{$context}/{$id}/{$image_name}.gif";
            if (file_exists($source_name)) {
                $source_is_gif = true;
                if ($mime !== 'gif') {
                    $relative_name = "{$relative_name_we}.gif";
                    $redir_url = "{$this->base_url}{$relative_name}";
                   // MailformedImageSpec::R($redir_url);
                }
            }
        }
        if (!file_exists($source_name)) { // если есть обработчик контекста - разрешить ему работу
            $source_name = "{$this->image_storage}{$context}/{$id}/{$image_name}.jpg";
            $class_name = "\\" . trim(__NAMESPACE__, "\\") . "\\ImageFlyContextHandler" . mb_strtoupper(mb_substr($context, 0, 1, 'UTF-8'), 'UTF-8') . mb_strtolower(mb_substr($context, 1, NULL, 'UTF-8'), 'UTF-8');
            if (class_exists($class_name, true) && \Helpers\Helpers::class_inherits($class_name, ImageFlyContextHandler::class)) {
                $class_name::on_source_not_found($source_name, $context, $id, $image_name);
            }
        }
        if (!file_exists($source_name)) {
            ImageFlyError::R("source image not found");
        }
        //флет
        // если файл уже существует - повторно не кропаем

        if (file_exists($cached_name)) {
            return new \Imagick($cached_name);
        }

        if ($source_is_gif) {
            if (!( file_exists($cached_path) && is_dir($cached_path) )) {
                mkdir($cached_path, 0777, true);
            }
            copy($source_name, $cached_name);
            copy($source_name, preg_replace("/\.gif$/i", ".jpg",$cached_name));
            return $cached_name; /// ну нахуй
            $image = new \Imagick($cached_name);
            return $image;
        }

        $image = new \Imagick($source_name);
        if ($use_crop) {// если нужен кроп - вырезаем интересующий нас фрагмент изображения
            $conf = ImageInfoManager::F()->get_image_info($context, $id, $image_name, $preset);
            if ($conf->can_be_cropped) {
                $image = $this->crop_image_by_image_info($image, $conf);
            }
        }
        // сводим в размер по условиям - нужно привести высоту и ширину отдельно?
        $result_image = $this->resize_image_to_required_dimensions($image, $width, $height, $crop_fill, $background ? "#{$background}" : MediaContextInfo::F()->get_context_default_background($context));
        if ($mime === "png") {
            $result_image->setimageformat('PNG32');
            if (MediaContextInfo::F()->get_context_allow_caching($context)) {
                if (!( file_exists($cached_path) && is_dir($cached_path) )) {
                    mkdir($cached_path, 0777, true);
                }
                $result_image->writeimage($cached_name);
            }
        } elseif ($mime === 'jpg') {
            $result_image->setimageformat("jpg");
            $result_image->setcompression(static::JPEG_COMPRESS_MODE);
            $result_image->setImageCompression(static::JPEG_COMPRESS_MODE);
            $result_image->setcompressionquality(static::JPEG_COMPRESS_LEVEL);
            $result_image->setimagecompressionquality(static::JPEG_COMPRESS_LEVEL);
            if (MediaContextInfo::F()->get_context_allow_caching($context)) {
                if (!( file_exists($cached_path) && is_dir($cached_path) )) {
                    mkdir($cached_path, 0777, true);
                }
                $result_image->writeimage($cached_name);
            }
        } else {
            ImageFlyError::RF("non supported out mime `%s`", $mime);
        }
        return $result_image;
    }

    protected function crop_image_by_image_info(\Imagick $image, ImageInfo $info): \IMagick {
        $current_width = $image->getimagewidth();
        $current_height = $image->getimageheight();
        $width_one_percent = $current_width / 100;
        $height_one_percent = $current_height / 100;
        $cropped_width_px = (int) Round(($info->end_x - $info->start_x) * $width_one_percent, 0);
        $cropped_height_px = (int) Round(($info->end_y - $info->start_y) * $height_one_percent, 0);
        $temp_image = new \Imagick();
        $temp_image->newimage($cropped_width_px, $cropped_height_px, new \ImagickPixel('rgba(0,0,0,0)'));
        $crop_position_x = max([(int) Round($info->crop_start_x * $width_one_percent, 0), 0]);
        $crop_position_y = max([(int) Round($info->crop_start_y * $height_one_percent, 0), 0]);
        // crop by minimum - do not over image (it is manual crop)
        $image->cropimage(min([$current_width, $cropped_width_px]), min([$current_height, $cropped_height_px]), $crop_position_x, $crop_position_y);
        $cropped_position_x = (int) Round(($cropped_width_px - $image->getimagewidth()) / 2, 0);
        $cropped_position_y = (int) Round(($cropped_height_px - $image->getimageheight()) / 2, 0);
        $temp_image->compositeimage($image, \Imagick::COMPOSITE_DEFAULT, $cropped_position_x, $cropped_position_y);
        return $temp_image;
    }

    protected function get_image_context_smile(string $context, string $id, string $image_name, int $width = 0, int $height = 0, bool $use_crop = true, bool $crop_fill = false, string $background = null, $related_image_spec = null, string $mime = "jpg", string $preset = null) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $image_spec = implode("", [
            "S",
            $width ? "W_{$width}" : "",
            $height ? "H_{$height}" : "",
            !$use_crop ? "C_0" : "",
            $crop_fill ? "CF_1" : "",
            $background ? "B_{$background}" : null
        ]);
        $relative_path = "{$context}/{$id}/";
        $relative_name = "{$relative_path}smile.{$image_spec}.{$mime}";
        if ($related_image_spec && ($related_image_spec !== $image_spec)) {
            $redir_url = "{$this->base_url}{$relative_name}";
            MailformedImageSpec::R($redir_url);
        }
        $cached_name = "{$this->image_base_web}{$relative_name}";
        $cached_path = "{$this->image_base_web}{$relative_path}";
        // если файл уже существует - повторно не кропаем
        if (file_exists($cached_name)) {
            return new \Imagick($cached_name);
        }
        $source_name = "{$this->image_storage}{$context}/{$id}/{$image_name}.png";
        if (!file_exists($source_name)) {
            ImageFlyError::R("source image not found");
        }
        $image = new \Imagick($source_name);
        if ($use_crop) {// если нужен кроп - вырезаем интересующий нас фрагмент изображения
            $conf = ImageInfoManager::F()->get_image_info($context, $id, $image_name, $preset);
            if ($conf->can_be_cropped) {
                $image = $this->crop_image_by_image_info($image, $conf);
            }
        }
        // сводим в размер по условиям - нужно привести высоту и ширину отдельно?
        $result_image = $this->resize_image_to_required_dimensions($image, $width, $height, $crop_fill, $background ? "#{$background}" : MediaContextInfo::F()->get_context_default_background($context));
        if ($mime === "png") {
            $result_image->setimageformat('PNG32');
            if (MediaContextInfo::F()->get_context_allow_caching($context)) {
                if (!( file_exists($cached_path) && is_dir($cached_path) )) {
                    mkdir($cached_path, 0777, true);
                }
                $result_image->writeimage($cached_name);
            }
        } else {
            ImageFlyError::RF("non supported out mime `%s`", $mime);
        }
        return $result_image;
    }

    protected function get_emoji(int $id, int $width = 0, int $height = 0, bool $use_crop = true, bool $crop_fill = true, string $background = null, $related_image_spec = null, $mime = "jpg") {
        $context = "emojirenderer";
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $image_spec = implode("", [
            "S",
            $width ? "W_{$width}" : "",
            $height ? "H_{$height}" : "",
            !$use_crop ? "C_0" : "",
            $crop_fill ? "CF_1" : "",
            $background ? "B_{$background}" : null
        ]);
        $relative_path = "{$context}/{$id}/";
        $relative_name = "{$relative_path}emoji.{$image_spec}.{$mime}";
        if ($related_image_spec && ($related_image_spec !== $image_spec)) {
            $redir_url = "{$this->base_url}{$relative_name}";
            MailformedImageSpec::R($redir_url);
        }
        $cached_name = "{$this->image_base_web}{$relative_name}";
        $cached_path = "{$this->image_base_web}{$relative_path}";
        // если файл уже существует - повторно не кропаем
        if (file_exists($cached_name)) {
            return new \Imagick($cached_name);
        }

        $image = new \Imagick();
        $emo = \Emoji\EmojiListItem::F()->load_db($id);
        $emo && $emo->image ? 0 : \Router\NotFoundError::R("not found");
        $pm = [];
        $s = preg_replace("/\n|\r|\s/", " ", $emo->image);
        if (!preg_match("/(?P<svg>\<svg\s{1,}.*\<\/svg\>)/i", $s, $pm)) {
            die($s);
            \Router\NotFoundError::R("invalid svg");
        }
        $svg = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?><!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">{$pm['svg']}";

        if ($background) {
            $image->setBackgroundColor("#$background");
        }
        $image->readImageBlob($svg);
        $current_width = $image->getimagewidth();
        $current_height = $image->getimageheight();

        $result_image = $this->resize_image_to_required_dimensions($image, $width, $height, $crop_fill, $background ? "#{$background}" : MediaContextInfo::F()->get_context_default_background($context));
        if ($mime === 'png') {
            $result_image->setimageformat('PNG32');
            if (MediaContextInfo::F()->get_context_allow_caching($context)) {
                if (!( file_exists($cached_path) && is_dir($cached_path) )) {
                    mkdir($cached_path, 0777, true);
                }
                $result_image->writeimage($cached_name);
            }
        } else if ($mime === "jpg") {
            $result_image->setimageformat("jpg");
            $result_image->setcompression(static::JPEG_COMPRESS_MODE);
            $result_image->setImageCompression(static::JPEG_COMPRESS_MODE);
            $result_image->setcompressionquality(static::JPEG_COMPRESS_LEVEL);
            $result_image->setimagecompressionquality(static::JPEG_COMPRESS_LEVEL);
            if (MediaContextInfo::F()->get_context_allow_caching($context)) {
                if (!( file_exists($cached_path) && is_dir($cached_path) )) {
                    mkdir($cached_path, 0777, true);
                }
                $result_image->writeimage($cached_name);
            }
        } else {
            ImageFlyError::RF("unsupported output fromat `%s`", $mime);
        }

        return $result_image;
    }

    protected function get_color(string $image_name, int $width = 0, int $height = 0, bool $use_crop = true, bool $crop_fill = false, string $background = null, $related_image_spec = null, $mime = "png") {
        $context = "_color";
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $image_spec = implode("", [
            "S",
            $width ? "W_{$width}" : "",
            $height ? "H_{$height}" : "",
            !$use_crop ? "C_0" : "",
            $crop_fill ? "CF_1" : "",
            $background ? "B_{$background}" : null
        ]);
        $relative_path = "{$context}/";
        $relative_name = "{$relative_path}{$image_name}.{$image_spec}.{$mime}";
        if ($related_image_spec && ($related_image_spec !== $image_spec)) {
            $redir_url = "{$this->base_url}{$relative_name}";
            MailformedImageSpec::R($redir_url);
        }
        $cached_name = "{$this->image_base_web}{$relative_name}";
        $cached_path = "{$this->image_base_web}{$relative_path}";
        $source_name = "{$this->image_storage}{$context}/{$image_name}.jpg";
        if (!file_exists($source_name)) {
            $source_name = "{$this->image_storage}{$context}/{$image_name}.png";
        }
        if (!file_exists($source_name)) {
            ImageFlyError::R("source image not found");
        }
        // если файл уже существует - повторно не кропаем
        if (file_exists($cached_name)) {
            return new \Imagick($cached_name);
        }
        $image = new \Imagick($source_name);
        $current_width = $image->getimagewidth();
        $current_height = $image->getimageheight();

        if ($use_crop) {// если нужен кроп - вырезаем интересующий нас фрагмент изображения
            $conf = ImageInfoManager::F()->get_color_info($image_name);
            if ($conf->can_be_cropped) {
                $image = $this->crop_image_by_image_info($image, $conf, $crop_fill);
            }
        }

        $current_width = $image->getimagewidth();
        $current_height = $image->getimageheight();

        $result_image = $this->resize_image_to_required_dimensions($image, $width, $height, $crop_fill, $background ? "#{$background}" : MediaContextInfo::F()->get_context_default_background($context));
        if ($mime === 'png') {
            $result_image->setimageformat('PNG32');
            if (MediaContextInfo::F()->get_context_allow_caching($context)) {
                if (!( file_exists($cached_path) && is_dir($cached_path) )) {
                    mkdir($cached_path, 0777, true);
                }
                $result_image->writeimage($cached_name);
            }
        } else if ($mime === "jpg") {
            $result_image->setimageformat("jpg");
            $result_image->setcompression(static::JPEG_COMPRESS_MODE);
            $result_image->setImageCompression(static::JPEG_COMPRESS_MODE);
            $result_image->setcompressionquality(static::JPEG_COMPRESS_LEVEL);
            $result_image->setimagecompressionquality(static::JPEG_COMPRESS_LEVEL);
            if (MediaContextInfo::F()->get_context_allow_caching($context)) {
                if (!( file_exists($cached_path) && is_dir($cached_path) )) {
                    mkdir($cached_path, 0777, true);
                }
                $result_image->writeimage($cached_name);
            }
        } else {
            ImageFlyError::RF("unsupported output fromat `%s`", $mime);
        }

        return $result_image;
    }

    public function get_image_source_filename(string $context, string $owner_id, string $image) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $source_name = "{$this->image_storage}{$context}/{$owner_id}/{$image}.jpg";        
        if (file_exists($source_name) && is_file($source_name) && is_readable($source_name)) {
            return $source_name;
        }
        ImageFlyError::R("source image not found");
    }
    
    public function get_image_source(string $context, string $owner_id, string $image) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $source_name = "{$this->image_storage}{$context}/{$owner_id}/{$image}.jpg";
        if (!file_exists($source_name)) {
            $source_name = "{$this->image_storage}{$context}/{$owner_id}/{$image}.png";
        }
        if (file_exists($source_name) && is_file($source_name) && is_readable($source_name)) {
            return new \Imagick($source_name);
        }
        ImageFlyError::R("source image not found");
    }

    public function get_color_source(string $image) {
        $source_name = "{$this->image_storage}_color/{$image}.jpg";
        if (!file_exists($source_name)) {
            $source_name = "{$this->image_storage}_color/{$image}.png";
        }
        if (file_exists($source_name) && is_file($source_name) && is_readable($source_name)) {
            return new \Imagick($source_name);
        }
        ImageFlyError::R("source image not found");
    }

    public function delete_color_cache(string $image) {
        $relative_path = "_color" . DIRECTORY_SEPARATOR;
        $cached_path = "{$this->image_base_web}{$relative_path}";
        \Out\Out::F()->add("test_imf", $cached_path);
        if (file_exists($cached_path) && is_dir($cached_path) && is_writable($cached_path)) {
            $ofc = 0;
            $list = scandir($cached_path);
            $c = 0;
            foreach ($list as $file_name) {
                $c++;
                \Out\Out::F()->add("test_imf_e{$c}", "{$cached_path}{$file_name}");
                if (strcasecmp(".", $file_name) !== 0 && strcasecmp("..", $file_name) !== 0) {
                    if (is_file("{$cached_path}{$file_name}") && is_writable("{$cached_path}{$file_name}") && preg_match("/^{$image}.*/i", $file_name)) {
                        unlink("{$cached_path}{$file_name}");
                        \Out\Out::F()->add("test_imf_e{$c}d", "{$cached_path}{$file_name}");
                    } else {
                        $ofc++;
                    }
                }
            }
            if ($ofc === 0) {
                rmdir(rtrim($cached_path, "\\/"));
            }
        }
    }

    /**
     * clear cache for specified image
     * @param string $context
     * @param string $owner_id
     * @param string $image
     */
    public function delete_image_cache(string $context, string $owner_id, string $image) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $relative_path = "{$context}/{$owner_id}/";
        $cached_path = "{$this->image_base_web}{$relative_path}";
        if (file_exists($cached_path) && is_dir($cached_path) && is_writable($cached_path)) {
            $ofc = 0;
            $list = scandir($cached_path);
            foreach ($list as $file_name) {
                if (strcasecmp(".", $file_name) !== 0 && strcasecmp("..", $file_name) !== 0) {
                    if (is_file("{$cached_path}{$file_name}") && is_writable("{$cached_path}{$file_name}") && preg_match("/^{$image}.*/i", $file_name)) {
                        unlink("{$cached_path}{$file_name}");
                    } else {
                        $ofc++;
                    }
                }
            }
            if ($ofc === 0) {
                rmdir(rtrim($cached_path, "\\/"));
            }
        }
    }

    /**
     * removes specified image
     * @param string $context
     * @param string $owner_id
     * @param string $image
     */
    public function remove_image(string $context, string $owner_id, string $image) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $relative_path = "{$context}/{$owner_id}/";
        $source_path = "{$this->image_storage}{$relative_path}";
        $source_file = "{$source_path}{$image}.png";
        if (file_exists($source_file) && is_file($source_file) && is_writable($source_file)) {
            unlink($source_file);
        }
        $source_file = "{$source_path}{$image}.jpg";
        if (file_exists($source_file) && is_file($source_file) && is_writable($source_file)) {
            unlink($source_file);
        }
        $this->delete_image_cache($context, $owner_id, $image);
        ImageInfoManager::F()->remove_image_data($context, $owner_id, $image);
    }

    /**
     * removes images for specified object
     * @param string $context
     * @param string $owner_id
     */
    public function remove_images(string $context, string $owner_id) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $relative_path = "{$context}/{$owner_id}/";
        $source_path = "{$this->image_storage}{$relative_path}";
        if (file_exists($source_path) && is_dir($source_path) && is_writable($source_path)) {
            \Helpers\Helpers::rm_dir_recursive($source_path);
        }
        $this->delete_images_cache($context, $owner_id);
        ImageInfoManager::F()->remove_images($context, $owner_id);
    }

    /**
     * removes cached images for specified context && owner
     * @param string $context
     * @param string $owner_id
     */
    public function delete_images_cache(string $context, string $owner_id) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $relative_path = "{$context}/{$owner_id}/";
        $cached_path = "{$this->image_base_web}{$relative_path}";
        if (file_exists($cached_path) && is_dir($cached_path) && is_writable($cached_path)) {
            \Helpers\Helpers::rm_dir_recursive($cached_path);
        }
    }

    /**
     * removes cached images for whole context
     * @param string $context
     */
    public function delete_context_cache(string $context) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $relative_path = "{$context}" . DIRECTORY_SEPARATOR;
        $cached_path = "{$this->image_base_web}{$relative_path}";
        if (file_exists($cached_path) && is_dir($cached_path) && is_writable($cached_path)) {
            \Helpers\Helpers::rm_dir_recursive($cached_path);
        }
    }

    /**
     * removes sources and caches for whole context
     * @param string $context
     */
    public function clear_media_context(string $context) {
        MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $relative_path = "{$context}" . DIRECTORY_SEPARATOR;
        $source_path = "{$this->image_storage}{$relative_path}";
        if (file_exists($source_path) && is_dir($source_path) && is_writable($source_path)) {
            \Helpers\Helpers::rm_dir_recursive($source_path);
        }
        $this->delete_context_cache($context);
        ImageInfoManager::F()->clear_context($context);
    }

    public function remove_color(string $image) {
        $this->remove_color_files($image);
        ImageInfoManager::F()->remove_color_data($image);
    }

    public function remove_color_files(string $image) {
        $relative_path = "_color/";
        $source_path = "{$this->image_storage}{$relative_path}";
        $source_file = "{$source_path}{$image}.png";
        if (file_exists($source_file) && is_file($source_file) && is_writable($source_file)) {
            unlink($source_file);
        }
        $source_file = "{$source_path}{$image}.jpg";
        if (file_exists($source_file) && is_file($source_file) && is_writable($source_file)) {
            unlink($source_file);
        }
        $this->delete_color_cache($image);
    }

    public function parse_color_request(string $request) {
        $m = [];
        if (preg_match("/^_color\/(?P<image_name>[0-9a-f\-]{36})\.S(?P<image_spec>.*)\.(?P<mime>png|jpg)$/i", trim($request, "\\/"), $m)) {
            $result = [
                'context' => "_color",
                'owner_id' => 100,
                'image_name' => $m['image_name'],
                'image_ext' => $m["mime"],
                "image_spec" => "S{$m['image_spec']}",
                "width" => 0,
                "height" => 0,
                "use_crop" => true,
                "background" => null,
                "crop_fill" => 0,
                'mime' => $m["mime"],
            ];
            if (!MediaContextInfo::F()->context_exists($result['context'])) {
                ImageFlyError::RF("unknown media context `%s`", $result['context']);
            }
            $mm = [];
            $image_spec = "S{$result['image_spec']}";
            if (preg_match("/W_(?P<size>\d{1,})/", $image_spec, $mm)) {
                $result['width'] = intval($mm['size']);
            }
            if (preg_match("/H_(?P<size>\d{1,})/", $image_spec, $mm)) {
                $result['height'] = intval($mm['size']);
            }
            if (preg_match("/C_(?P<crop>(1|0))/", $image_spec, $mm)) {
                $result['use_crop'] = intval($mm['crop']) ? true : false;
            }
            if (preg_match("/CF_(?P<crop_fill>(1|0))/", $image_spec, $mm)) {
                $result['crop_fill'] = intval($mm['crop_fill']) ? true : false;
            }
            if (preg_match("/B_(?P<bc>[a-f0-9]{6,8})/i", $image_spec, $mm)) {
                $result['background'] = "{$mm['bc']}";
            }
            $ret = ImageFlyRequest::F($result);
            if ($ret->valid) {
                return $ret;
            }
        }
        ImageFlyError::RF("can not parse request `%s`", $request);
    }

    /**
     * 
     * @param string $request
     * @return ImageFlyRequest
     */
    public function parse_request(string $request) {
        if (preg_match("/^_color\/(?P<image_name>[0-9a-f\-]{36})\.S(?P<image_spec>.*)\.(?P<mime>png|jpg)$/i", trim($request, "\\/"))) {
            return $this->parse_color_request($request);
        }
        if (preg_match("/^fallback\//i", trim($request, "\\/"))) {
            return $this->parse_fallback_request($request);
        }
        if (preg_match("/^emojirenderer\//i", trim($request, "\\/"))) {
            return $this->parse_emoji_request($request);
        }
        if (preg_match("/^SMILE\//i", trim($request, "\\/"))) {
            return $this->parse_emoji_request_2($request);
        }
        $m = [];
        if (preg_match("/^(?P<context>[^\/]{1,})\/(?P<owner_id>[^\/]{1,})\/(?P<image_name>[0-9a-f]{32})\.S(?P<image_spec>.*)\.(?P<mime>png|jpg|gif)$/i", trim($request, "\\/"), $m)) {
            $result = [
                'context' => $m['context'],
                'owner_id' => $m['owner_id'],
                'image_name' => $m['image_name'],
                'image_ext' => $m["mime"],
                "image_spec" => "S{$m['image_spec']}",
                "width" => 0,
                "height" => 0,
                "use_crop" => true,
                "background" => null,
                "mime" => $m["mime"],
                "preset" => "none",
                'crop_fill' => 0, // fill the crop
            ];
            if (!MediaContextInfo::F()->context_exists($result['context'])) {
                ImageFlyError::RF("unknown media context `%s`", $result['context']);
            }
            $mm = [];
            $image_spec = "S{$result['image_spec']}";
            if (preg_match("/W_(?P<size>\d{1,})/", $image_spec, $mm)) {
                $result['width'] = intval($mm['size']);
            }
            if (preg_match("/H_(?P<size>\d{1,})/", $image_spec, $mm)) {
                $result['height'] = intval($mm['size']);
            }
            if (preg_match("/C_(?P<crop>(1|0))/", $image_spec, $mm)) {
                $result['use_crop'] = intval($mm['crop']) ? true : false;
            }
            if (preg_match("/CF_(?P<crop_fill>(1|0))/", $image_spec, $mm)) {
                $result['crop_fill'] = intval($mm['crop_fill']) ? true : false;
            }
            if (preg_match("/B_(?P<bc>[a-f0-9]{6,8})/i", $image_spec, $mm)) {
                $result['background'] = "{$mm['bc']}";
            }
            if (preg_match("/PR_(?P<preset>[a-z0-9]{1,})/i", $image_spec, $mm)) {
                $result['preset'] = "{$mm['preset']}";
            }

            $ret = ImageFlyRequest::F($result);
            if ($ret->valid) {
                return $ret;
            }
        }
        ImageFlyError::RF("can not parse request `%s`", $request);
    }

    public function parse_emoji_request(string $request) {
        $m = [];
        if (preg_match("/^emojirenderer\/(?P<id>\d{1,})\/emoji.S(?P<image_spec>.*)\.(?P<mime>png|jpg)$/i", trim($request, "\\/"), $m)) {
            $result = [
                'context' => "SMILE", // "emojirenderer",
                'owner_id' => $m['id'],
                'image_name' => "smile", //emoji",
                'image_ext' => $m["mime"],
                "image_spec" => "S{$m['image_spec']}",
                "mime" => "png", //$m["mime"],
                "width" => 0,
                "height" => 0,
                "use_crop" => true,
                "background" => null,
                "preset" => "none",
                'crop_fill' => 0, // fill the crop
            ];
            //MediaContextInfo::F()->register_media_context("emojirenderer", 1000, 1000, 5, 5);
            if (!MediaContextInfo::F()->context_exists($result['context'])) {
                ImageFlyError::RF("unknown media context `%s`", $result['context']);
            }
            $mm = [];
            $image_spec = "S{$result['image_spec']}";
            if (preg_match("/W_(?P<size>\d{1,})/", $image_spec, $mm)) {
                $result['width'] = intval($mm['size']);
                $result['height'] = intval($mm['size']); //always square
            }
            if (preg_match("/H_(?P<size>\d{1,})/", $image_spec, $mm)) {
                // $result['height'] = intval($mm['size']);
            }
            if (preg_match("/C_(?P<crop>(1|0))/", $image_spec, $mm)) {
                $result['use_crop'] = intval($mm['crop']) ? true : false;
            }
            if (preg_match("/CF_(?P<crop_fill>(1|0))/", $image_spec, $mm)) {
                $result['crop_fill'] = intval($mm['crop_fill']) ? true : false;
            }
            if (preg_match("/B_(?P<bc>[a-f0-9]{6,8})/i", $image_spec, $mm)) {
                $result['background'] = "{$mm['bc']}";
            }
            if (preg_match("/PR_(?P<preset>[a-z0-9]{1,})/i", $image_spec, $mm)) {
                $result['preset'] = "{$mm['preset']}";
            }
            $ret = ImageFlyRequest::F($result);
            if ($ret->valid) {
                return $ret;
            }
        }
        ImageFlyError::RF("can not parse request `%s`", $request);
    }

    public function parse_emoji_request_2(string $request) {
        $m = [];
        if (preg_match("/^SMILE\/(?P<id>\d{1,})\/smile.S(?P<image_spec>.*)\.(?P<mime>png|jpg)$/i", trim($request, "\\/"), $m)) {
            $result = [
                'context' => "SMILE",
                'owner_id' => $m['id'],
                'image_name' => "smile",
                'image_ext' => $m["mime"],
                "image_spec" => "S{$m['image_spec']}",
                "mime" => $m["mime"],
                "width" => 0,
                "height" => 0,
                "use_crop" => true,
                "background" => null,
                "preset" => "none",
                'crop_fill' => 0, // fill the crop
            ];
            //MediaContextInfo::F()->register_media_context("emojirenderer", 1000, 1000, 5, 5);
            if (!MediaContextInfo::F()->context_exists($result['context'])) {
                ImageFlyError::RF("unknown media context `%s`", $result['context']);
            }
            $mm = [];
            $image_spec = "S{$result['image_spec']}";
            if (preg_match("/W_(?P<size>\d{1,})/", $image_spec, $mm)) {
                $result['width'] = intval($mm['size']);
                $result['height'] = intval($mm['size']); //always square
            }
            if (preg_match("/H_(?P<size>\d{1,})/", $image_spec, $mm)) {
                // $result['height'] = intval($mm['size']);
            }
            if (preg_match("/C_(?P<crop>(1|0))/", $image_spec, $mm)) {
                $result['use_crop'] = intval($mm['crop']) ? true : false;
            }
            if (preg_match("/CF_(?P<crop_fill>(1|0))/", $image_spec, $mm)) {
                $result['crop_fill'] = intval($mm['crop_fill']) ? true : false;
            }
            if (preg_match("/B_(?P<bc>[a-f0-9]{6,8})/i", $image_spec, $mm)) {
                $result['background'] = "{$mm['bc']}";
            }
            if (preg_match("/PR_(?P<preset>[a-z0-9]{1,})/i", $image_spec, $mm)) {
                $result['preset'] = "{$mm['preset']}";
            }
            $ret = ImageFlyRequest::F($result);
            if ($ret->valid) {
                return $ret;
            }
        }
        ImageFlyError::RF("can not parse request `%s`", $request);
    }

    public function parse_fallback_request(string $request) {
        $m = [];
        if (preg_match("/^fallback\/1\/(?P<image_name>[^\.]{1,})\.S(?P<image_spec>.*)\.(?P<mime>png|jpg)$/i", trim($request, "\\/"), $m)) {
            $result = [
                'context' => "fallback",
                'owner_id' => "1",
                'image_name' => $m['image_name'],
                'image_ext' => $m["mime"],
                "image_spec" => "S{$m['image_spec']}",
                "mime" => $m["mime"],
                "width" => 0,
                "height" => 0,
                "use_crop" => true,
                "background" => null,
                "preset" => "none",
                'crop_fill' => 0, // fill the crop
            ];
            if (!MediaContextInfo::F()->context_exists($result['context'])) {
                ImageFlyError::RF("unknown media context `%s`", $result['context']);
            }
            $mm = [];
            $image_spec = "S{$result['image_spec']}";
            if (preg_match("/W_(?P<size>\d{1,})/", $image_spec, $mm)) {
                $result['width'] = intval($mm['size']);
            }
            if (preg_match("/H_(?P<size>\d{1,})/", $image_spec, $mm)) {
                $result['height'] = intval($mm['size']);
            }
            if (preg_match("/C_(?P<crop>(1|0))/", $image_spec, $mm)) {
                $result['use_crop'] = intval($mm['crop']) ? true : false;
            }
            if (preg_match("/CF_(?P<crop_fill>(1|0))/", $image_spec, $mm)) {
                $result['crop_fill'] = intval($mm['crop_fill']) ? true : false;
            }
            if (preg_match("/B_(?P<bc>[a-f0-9]{6,8})/i", $image_spec, $mm)) {
                $result['background'] = "{$mm['bc']}";
            }
            if (preg_match("/PR_(?P<preset>[a-z0-9]{1,})/i", $image_spec, $mm)) {
                $result['preset'] = "{$mm['preset']}";
            }
            $ret = ImageFlyRequest::F($result);
            if ($ret->valid) {
                return $ret;
            }
        }
        ImageFlyError::RF("can not parse request `%s`", $request);
    }

    public function clear_orphaned_color_files() {
        return;
        $context = IMediaContext::_COLOR;
        $relative_path = "{$context}" . DIRECTORY_SEPARATOR;
        $source_path = "{$this->image_storage}{$relative_path}";
        $all_files = scandir($source_path);
        foreach ($all_files as $file_name) {
            $path = $source_path . $file_name;
            $m = [];
            if (is_file($path) && is_readable($path) && is_writeable($path) && preg_match("/^(?P<i>[^\.]{1,})\.(png|jpg)$/i", $file_name, $m)) {
                if (!ImageInfoManager::F()->check_color_image_exists($m['i'])) {
                    $this->remove_color_files($m['i']);
                }
            }
        }
    }

    public function set_image_properties(string $context, string $owner_id, string $image, array $props) {
        ImageInfoManager::F()->set_image_properties($context, $owner_id, $image, $props);
    }

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @param array $images [image=>["prop"=>value,"prop"=>value],image=>[]]
     */
    public function set_images_properties(string $context, string $owner_id, array $images) {
        ImageInfoManager::F()->set_images_properties($context, $owner_id, $images);
    }

    public function clear_all_caches() {
        $base = rtrim(\Config\Config::F()->IMAGE_WEB_BASE_PATH, DIRECTORY_SEPARATOR);
        if (file_exists($base) && is_dir($base) && is_writable($base) && is_readable($base)) {
            $list = scandir($base);
            foreach ($list as $name) {
                if (mb_substr($name, 0, 1, "UTF-8") !== ".") {
                    if (is_dir($base . DIRECTORY_SEPARATOR . $name)) {
                        \Helpers\Helpers::rm_dir_recursive($base . DIRECTORY_SEPARATOR . $name);
                    }
                }
            }
        }
    }

    public function image_exists(string $context, string $owner_id, string $image_id): bool {
        try {
            MediaContextInfo::F()->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
            $source_name = "{$this->image_storage}{$context}/{$owner_id}/{$image_id}.jpg";
            if (!file_exists($source_name)) {
                $source_name = "{$this->image_storage}{$context}/{$owner_id}/{$image_id}.png";
            }
            if (file_exists($source_name) && is_file($source_name) && is_readable($source_name)) {
                return true;
            }
            ImageFlyError::R("source image not found");
        } catch (\Throwable $e) {
            
        }
        return false;
    }

}
