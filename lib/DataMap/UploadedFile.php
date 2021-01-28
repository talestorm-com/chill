<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

/**
 * @property string $field_name
 * @property string $tmp_name
 * @property int $size
 * @property string $name
 * @property bool $valid
 * @property string $type
 */
class UploadedFile {

    use \common_accessors\TCommonAccess;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $tmp_name;

    /** @var string */
    protected $field_name;

    /** @var int */
    protected $size;

    /** @var string */
    protected $name;

    /** @var string */
    protected $type;

    /** @var bool */
    protected $valid;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__tmp_name() {
        return $this->tmp_name;
    }

    /** @return int */
    protected function __get__size() {
        return $this->size;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->valid;
    }

    /** @return string */
    protected function __get__type() {
        return $this->type;
    }

    /** @return string */
    protected function __get__field_name() {
        return $this->field_name;
    }

    //</editor-fold>


    public function move(string $target) {
        move_uploaded_file($this->tmp_name, $target);
    }

    public function __construct(string $field_name, array $field_data) {
        $this->field_name = $field_name;
        $this->size = intval($field_data['size']);
        $this->name = trim($field_data['name']);
        $this->type = trim($field_data['type']);
        $this->tmp_name = trim($field_data['tmp_name']);
        $this->valid = false;
        if ($field_data['error'] === UPLOAD_ERR_OK) {
            if ($this->size > 0) {
                if ($this->name && mb_strlen($this->name, 'UTF-8') > 0 && $this->name !== 'none') {
                    if (is_uploaded_file($this->tmp_name)) {
                        $this->valid = true;
                    }
                }
            }
        }
    }

    /**
     * 
     * @param string $field_name
     * @param array $data
     * @return \static
     */
    public static function F(string $field_name, array $data) {
        return new static($field_name, $data);
    }

    public function get_source_extension(): string {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function get_source_extension_dotted(): string {
        $extension = \Helpers\Helpers::NEString($this->get_source_extension(), null);
        return $extension ? ".{$extension}" : "";
    }

}
