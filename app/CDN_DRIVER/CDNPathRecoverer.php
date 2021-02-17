<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CDN_DRIVER;

/**
 * Description of CDNPathReoverer
 *
 * @author eve
 */
class CDNPathRecoverer {

    public static function recover_path(string $path) {
        $path = trim(str_ireplace("\\", "/", $path), "/");
        $apath = explode("/", $path);
        $rpath = [];
        foreach ($apath as $path_component) {
            if ($path_component === ".") {
                continue;
            }
            if ($path_component === "..") {
                if (count($rpath)) {
                    $rpath = array_slice($rpath, 0, count($rpath) - 1);
                } else {
                    $rpath = [];
                }
                continue;
                ;
            }
            if (mb_strlen(trim($path_component), 'UTF-8')) {
                $rpath[] = trim($path_component);
            }
        }
        return "/" . implode("/", $rpath);
    }

}
