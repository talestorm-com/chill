<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\FileList;

/**
 * Description of FileListItem
 *
 * @author eve
 * @property int $content_id
 * @property string $cdn_id
 * @property bool $enabled
 * @property string $size
 * @property string $info
 * @property string $content_type
 * @property string $selector
 * @property int $sort
 */
class FileListItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $content_id;

    /** @var string */
    protected $cdn_id;

    /** @var bool */
    protected $enabled;

    /** @var string */
    protected $size;

    /** @var string */
    protected $info;

    /** @var string */
    protected $content_type;

    /** @var string */
    protected $selector;

    /** @var int */
    protected $sort;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__content_id() {
        return $this->content_id;
    }

    /** @return string */
    protected function __get__cdn_id() {
        return $this->cdn_id;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return string */
    protected function __get__size() {
        return $this->size;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return string */
    protected function __get__content_type() {
        return $this->content_type;
    }

    /** @return string */
    protected function __get__selector() {
        return $this->selector;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    //</editor-fold>


    protected function __construct(array $data) {
        $this->import_props($data);
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data) {
        return new static($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'content_id' => ['IntMore0'], //int
            'cdn_id' => ['Strip', 'Trim', 'NEString'], //string
            'enabled' => ['Boolean', 'DefaultTrue'], //bool
            'size' => ['Strip', 'Trim', 'NEString'], //string
            'info' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'content_type' => ['Strip', 'Trim', 'NEString'],
            'selector' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'sort' => ['Int', 'Default0'],
        ];
    }

}
