<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function include_css($file_name){
    ob_start();
    include $file_name;
    return ob_get_clean();
}


function encode_svg($file_name){
    $content = file_get_contents($file_name);
    $content = str_ireplace(["\n","\r","\t"], " ", $content);
    $content = str_ireplace("'", "\"", $content);
    return "data:image/svg+xml;utf8,".$content;
}

$dir = __DIR__ . DIRECTORY_SEPARATOR;
$result = [];
$list = scandir($dir);
foreach ($list as $fn) {
    $m = [];
    if (preg_match("/^(?P<n>[^\.].*)\.css$/", $fn, $m)) {
        if (is_file($dir . $fn)) {
            $result[$m['n']] = include_css($dir . $fn);
        }
    }
}
foreach ($result as $file => $style) {
    file_put_contents($dir . "{$file}.json", json_encode(["style" => $style]));
}

echo "\n\ndone\n\n";
