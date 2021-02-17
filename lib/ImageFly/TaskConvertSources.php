<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

class TaskConvertSources extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name() {
        return "convert_sources";
    }

    protected function exec() {
        $base_path = rtrim(\Config\Config::F()->IMAGE_STORAGE_PATH, DIRECTORY_SEPARATOR);
        $this->run_recursive($base_path);
        $this->log("conversion success. clearing caches");
        ImageFly::F()->clear_all_caches();
        $this->log("done");
    }

    protected function run_recursive($root) {
        $this->log(sprintf("processing dir `%s`", $root));
        if (file_exists($root) && is_dir($root)) {
            $list = scandir($root);
            foreach ($list as $name) {
                if (mb_substr($name, 0, 1, "UTF-8") !== ".") {
                    $path = $root . DIRECTORY_SEPARATOR . $name;
                    if (is_dir($path)) {
                        $this->run_recursive($path);
                    } else if (is_file($path)) {
                        $this->process_file($root, $name);
                    }
                }
            }
        }
    }

    protected function process_file($root, $name) {
        $m = [];
        if (preg_match("/^(?P<px>.*)\.(?P<ext>png|jpg)$/i", $name, $m)) {
            if (strcasecmp($m['ext'], 'png') === 0) {
                $this->log(sprintf("found `%s`", $root . DIRECTORY_SEPARATOR . $name));
                if (file_exists($root . DIRECTORY_SEPARATOR . $m['px'] . ".jpg")) {
                    $this->log("skip, destination exists");
                } else {
                    $this->log("processing");
                    $image = new \Imagick($root . DIRECTORY_SEPARATOR . $name);
                    $image->setimageformat("jpg");
                    $image->setcompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
                    $image->setcompressionquality(95);
                    $image->setImagecompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
                    $image->setImagecompressionquality(95);
                    $image->writeimage($root.DIRECTORY_SEPARATOR.$m["px"].".jpg");
                    $this->log("converted");
                    $image->destroy();
                    $image = null;
                }
                $this->log("removing source");
                @unlink($root . DIRECTORY_SEPARATOR . $name);
            }
        }
    }

}
