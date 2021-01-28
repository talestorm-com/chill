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
class FakeUploadedFSFile extends UploadedFile {

    use \common_accessors\TCommonAccess;

    private static $_counter = 0;
    private static $_tmp_dir = null;

    private static function counter() {
        static::$_counter++;
        return static::$_counter;
    }

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
        rename($this->tmp_name, $target);
    }

    private function get_tmp_dir() {
        if (!static::$_tmp_dir) {
            static::$_tmp_dir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "fake_uploaded_file" . DIRECTORY_SEPARATOR;
            if (!(file_exists(static::$_tmp_dir) && is_dir(static::$_tmp_dir) && is_writable(static::$_tmp_dir))) {
                @mkdir(static::$_tmp_dir, 0777, true);
            }
        }

        return static::$_tmp_dir;
    }

    public function set_content_type(string $ct = "application/octet-stream") {
        $this->type = $ct;
    }

    public function __construct(string $file_path) {
        $tmp_nam = $file_path;
        $this->field_name = "fake_file_" . static::counter();
     //   file_put_contents($tmp_nam, base64_decode($encode_image_b64));
        $this->size = filesize($tmp_nam);
        $this->name = $this->field_name;
        $this->type = "application/octet-stream";
        $this->tmp_name = $tmp_nam;
        $this->valid = false;
        if ($this->size > 0) {
            $this->valid = true;
        } else {
            if (file_exists($tmp_nam) && is_file($tmp_nam)) {
                @unlink($tmp_nam);
            }
        }
    }

    public function __destruct() {
        if (file_exists($this->tmp_name) && is_file($this->tmp_name) && is_writable($this->tmp_name)) {
            @unlink($this->tmp_name);
        }
    }

    /**
     * 
     * @param string $file_path
     * @return \static
     */
    public static function F(string $file_path) {
        return new static($file_path);
    }

}
