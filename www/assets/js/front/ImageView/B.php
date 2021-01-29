<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace f8d38710a2d4470c848f95f69d3eef5c;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . "__bootstrap.php";

class B {

    protected $dir;
    protected $t_dir;

    protected function __construct() {
        $this->dir = __DIR__ . DIRECTORY_SEPARATOR;
        $this->t_dir = "{$this->dir}T" . DIRECTORY_SEPARATOR;
        $this->run();
    }

    protected function collect_templates() {
        $templates = [];
        if (file_exists($this->t_dir) && is_dir($this->t_dir)) {
            $list = scandir($this->t_dir);
            foreach ($list as $name) {
                if (mb_substr($name, 0, 1, 'UTF-8') !== '.') {
                    $m = [];
                    if (preg_match("/^(?P<n>.*)\.html$/i", $name, $m)) {
                        if (is_file("{$this->t_dir}{$name}")) {
                            $templates[$m['n']] = file_get_contents("{$this->t_dir}{$name}");
                        }
                    }
                }
            }
        }
        return $templates;
    }

    protected function collect_style() {
        $style = file_get_contents("{$this->t_dir}image_view.css");
        $style = \Out\assets\minifiers\AssetMinifier::F()->minify_css($style);
        return ["style" => $style];
    }

    protected function run() {
        $templates = $this->collect_templates();
        $style = $this->collect_style();
        $text = file_get_contents("{$this->dir}ImageView_dev.js");
        $text = str_ireplace("/*HERE_TEMPLATES*/", "TEMPLATES=" . json_encode($templates), $text);
        $text = str_ireplace("/*HERE_STYLES*/", "STYLES=" . json_encode($style), $text);
        if (!\DataMap\GPDataMap::F()->exists("ncjs")) {
            $text = \Out\assets\minifiers\AssetMinifier::F()->minify_js($text);
        }
        file_put_contents("{$this->dir}image_view.min.js", $text);
        die('done');
    }

    public static function F() {
        return new static();
    }

}

B::F();
