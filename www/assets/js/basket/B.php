<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of B
 *
 * @author studio2
 */
class B {

    protected function __construct() {
        $pti = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR. ".." . DIRECTORY_SEPARATOR . "__bootstrap.php";              
        require_once $pti;
    }

    public static function F() {
        return new static();
    }

    public function templates($varname = 'T', $dirname = null) {
        $dirname = $dirname ? $dirname : $varname;
        $path = __DIR__ . DIRECTORY_SEPARATOR . $dirname . DIRECTORY_SEPARATOR;
        $result = ['dummy' => ''];
        if (file_exists($path) && is_dir($path)) {
            $list = scandir($path);
            foreach ($list as $file_name) {
                $m = [];
                if (preg_match("/^(?P<nam>[^\.]{1,})\.html$/i", $file_name, $m)) {
                    if (file_exists("{$path}{$file_name}") && is_file("{$path}{$file_name}") && is_readable("{$path}{$file_name}")) {
                        $result[$m['nam']] = file_get_contents("{$path}{$file_name}");
                    }
                }
            }
        }
        $json = json_encode($result);
        return "*/{$varname} = {$json};/*";
    }

    protected function process_file($filename) {
        ob_start();
        include $filename;
        $text = ob_get_clean();
        if (!\DataMap\GPDataMap::F()->get_filtered("ncjs", ["Boolean", "DefaultFalse"])) {            
            $text = \Out\assets\minifiers\AssetMinifier::F()->minify_js($text);
        }
        return $text;
    }

    public function run() {
        $in_files = [
            "product_params_manager",
            "basket_request",
        ];
        $output = [];
        foreach ($in_files as $file) {
            $in_file = __DIR__ . DIRECTORY_SEPARATOR . "{$file}_dev.js";
            if (file_exists($in_file) && is_file($in_file) && is_readable($in_file)) {
                $output[] = $this->process_file($in_file);
            }
        }        
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "basket.min.js", implode(";\n", $output));
        die("done");
    }

}

B::F()->run();
