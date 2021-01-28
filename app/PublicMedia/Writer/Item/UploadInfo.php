<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Item;

/**
 * Description of UploadInfo
 * Инфа о загрузке
 * 
 * @author eve
 * 
 * @property \DataMap\UploadedFile $file
 * @property \DataMap\UploadedFile $preview
 * @property string $file_type
 * @property string $file_extension
 * @property string $dotted_file_extension
 * @property \ImageFly\ImageMeasureResult $measurement
 * @property \ImageFly\ImageMeasureResult $preview_measurement
 * @property bool $has_file
 * @property bool $is_video
 * @property bool $has_preview
 */
class UploadInfo {

    use \common_accessors\TCommonAccess;

    protected $file;
    protected $preview;
    protected $file_type;
    protected $file_extension;
    protected $measurement;
    protected $preview_measurement;

    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return \DataMap\UploadedFile */
    protected function __get__file() {
        return $this->file;
    }

    /** @return \DataMap\UploadedFile */
    protected function __get__preview() {
        return $this->preview;
    }

    /** @return string */
    protected function __get__file_type() {
        return $this->file_type;
    }

    /** @return string */
    protected function __get__file_extension() {
        return $this->file_extension;
    }

    /** @return \ImageFly\ImageMeasureResult */
    protected function __get__measurement() {
        return $this->measurement;
    }

    /** @return \ImageFly\ImageMeasureResult */
    protected function __get__preview_measurement() {
        return $this->preview_measurement;
    }

    /** @return bool */
    protected function __get__has_file() {
        return $this->file ? true : false;
    }

    /** @return bool */
    protected function __get__is_video() {
        return $this->measurement->type === \ImageFly\ImageMeasureResult::IMAGE_TYPE_VIDEO;
    }

    /** @return bool */
    protected function __get__has_preview() {
        return $this->preview ? true : false;
    }

    /** @return string */
    protected function __get__dotted_file_extension() {
        $x = \Helpers\Helpers::NEString($this->file_extension, null);
        return $x ? ".{$x}" : "";
    }

    //</editor-fold>

    /**
     * Фиксировать имя поля - нужна возможность грузить прев отдельно
     */
    public function __construct() {

        $map = \DataMap\FileMap::F();
        $media = $map->get_by_field_name('media');
        if (count($media)) {
            $this->file = $media[0];
            $this->file_type = $this->file->type;
            $this->file_extension = $this->file->get_source_extension();
            $this->measure_file();
        }
        $preview = $map->get_by_field_name('preview');
        if (count($preview)) {
            $this->preview = $preview[0];
            $this->measure_preview();
        }
    }

    protected function measure_preview() {
        if ($this->preview) {
            try {
                $this->preview_measurement = \ImageFly\ImageInfoDetector::F()->detect_file($this->preview);
                if ($this->preview_measurement && \ImageFly\ImageMeasureResult::IMAGE_TYPE_IMAGE !== $this->preview_measurement->type) {
                    \Errors\common_error::R("invalid preview type");
                }
            } catch (\Throwable $e) {
                $this->preview = null;
                $this->preview_measurement = null;
            }
        }
    }

    protected function measure_file() {
        try {
            $this->measurement = \ImageFly\ImageInfoDetector::F()->detect_file($this->file);
        } catch (\Throwable $e) {
            $this->measurement = null;
        }
    }

    public static function F(): UploadInfo {
        return new static();
    }

}
