<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront\FileList;

/**
 * Description of FileListItem
 *
 * @author eve
 * @property string $cdn_id
 * @property string $size
 * @property string $info
 * @property string $content_type
 * @property int $sort
 * @property string $selector
 */
class FileListItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $cdn_id;

    /** @var string */
    protected $size;

    /** @var string */
    protected $info;

    /** @var string */
    protected $content_type;

    /** @var int */
    protected $sort;

    /** @var string */
    protected $selector;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__cdn_id() {
        return $this->cdn_id;
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

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return string */
    protected function __get__selector() {
        return $this->selector;
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
            'cdn_id' => ['Strip', 'Trim', 'NEString'], //string            
            'size' => ['Strip', 'Trim', 'NEString'], //string
            'info' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'content_type' => ['Strip', 'Trim', 'NEString'],
            'sort' => ['Int', 'Default0'], //int
            'selector' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

}
