<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dir = __DIR__ . DIRECTORY_SEPARATOR;
$result = [];
$list = scandir($dir);
foreach ($list as $fn) {
    $m = [];
    if (preg_match("/^(?P<n>[^\.].*)\.html$/", $fn, $m)) {
        if (is_file($dir . $fn)) {
            $result[$m['n']] = file_get_contents($dir . $fn);
        }
    }
}

file_put_contents($dir . "templates.json", json_encode($result));
echo "\n\ndone\n\n";
