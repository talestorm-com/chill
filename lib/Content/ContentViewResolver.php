<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content;

/**
 * ресолвер путей виевов контента.
 * @author studio2
 * 
 * @property string $base_path
 */
class ContentViewResolver {

    use \common_accessors\TCommonAccess;

    private static $instance;

    /** @var string */
    private $base_path;

    protected function __get__base_path() {
        return $this->base_path;
    }

    protected function init_base_path() {
        $this->base_path = \Config\Config::F()->VIEW_PATH . "modules" . DIRECTORY_SEPARATOR . "content" . DIRECTORY_SEPARATOR;
    }

    private function __construct() {
        static::$instance = $this;
        $this->init_base_path();
        if (!(file_exists($this->base_path) && (is_dir($this->base_path)) && (is_readable($this->base_path)) )) {
            @mkdir($this->base_path, 0777, true);
        }
        if (!(file_exists($this->base_path) && (is_dir($this->base_path)) && (is_readable($this->base_path)) )) {
            \Errors\common_error::RF("cant access views path `%s` in `%s`", $this->base_path, get_called_class());
        }
    }

    public function resolve_path_for_class(string $class_name, string $view = null) {
        $view ? 0 : $view = 'default';
        $path_dir = $this->base_path . $this->clear_class_name($class_name) . DIRECTORY_SEPARATOR;
        if (!(file_exists($path_dir) && is_dir($path_dir) && is_readable($path_dir))) {
            @mkdir($path_dir, 0777, true);
        }
        if (!(file_exists($path_dir) && is_dir($path_dir) && is_readable($path_dir))) {
            \Errors\common_error::RF("cat create module view dir `%s` at %s", $path_dir, get_called_class());
        }
        $view_path = "{$path_dir}{$view}.tpl";
        if (!(file_exists($view_path) && is_file($view_path) && is_readable($view_path))) {
            \Errors\common_error::RF("cant locate view `%s` for class `%s` at path `%s`", $view, $class_name, $view_path);
            //return null;
        }
        return $view_path;
    }

    public function clear_class_name(string $class_name) {
        $m = explode("\\", $class_name);
        return $m[count($m) - 1];
    }

    public function resolve_path_for_object($object, string $view = null) {
        is_object($object) ? 0 : \Errors\common_error::RF("`%s` requires an object", __METHOD__);
        $cs = get_class($object);
        return $this->resolve_path_for_class($cs, $view);
    }

    public function get_templates_dir_for_class(string $class_name, string $subdir = null) {
        $path_dir = $this->base_path . $this->clear_class_name($class_name) . DIRECTORY_SEPARATOR;
        if (!(file_exists($path_dir) && is_dir($path_dir) && is_readable($path_dir))) {
            @mkdir($path_dir, 0777, true);
        }
        if (!(file_exists($path_dir) && is_dir($path_dir) && is_readable($path_dir))) {
            \Errors\common_error::RF("cat create module view dir `%s` at %s", $path_dir, get_called_class());
        }
        if ($subdir) {
            $subdir = \Helpers\Helpers::NEString(trim(str_ireplace(["\\/"], DIRECTORY_SEPARATOR, $subdir), DIRECTORY_SEPARATOR), null);
            if ($subdir) {
                $subdir = str_ireplace(".", "", $subdir);
                $subdir = \Helpers\Helpers::NEString(trim(str_ireplace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $subdir), DIRECTORY_SEPARATOR), null);
                if ($subdir) {
                    $path_dir = $path_dir . $subdir . DIRECTORY_SEPARATOR;
                    if (!(file_exists($path_dir) && is_dir($path_dir) && is_readable($path_dir))) {
                        @mkdir($path_dir, 0777, true);
                    }
                    if (!(file_exists($path_dir) && is_dir($path_dir) && is_readable($path_dir))) {
                        \Errors\common_error::RF("cat create module view dir `%s` at %s", $path_dir, get_called_class());
                    }
                }
            }
        }
        return $path_dir;
    }

    public function get_templates_dir_for_object($object, string $subdir = null) {
        is_object($object) ? 0 : \Errors\common_error::RF("`%s` requires an object", __METHOD__);
        $cs = get_class($object);
        return $this->get_templates_dir_for_class($cs, $subdir);
    }

    public static function F(): ContentViewResolver {
        return static::$instance ? static::$instance : new static();
    }

}
