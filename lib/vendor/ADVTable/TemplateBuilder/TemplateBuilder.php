<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\TemplateBuilder;

class TemplateBuilder {

    //нужна корневая папка - откуда читать шаблоны и постфикс
    protected static $instance;

    protected function __construct() {
        static::$instance = $this;
    }

    protected function searchFiles($base, array &$o) {
        $list = scandir($base);
        foreach ($list as $file) {
            $m = [];
            if (!preg_match('/^\./', $file)) {
                if (is_file($base . $file) && preg_match('/^(?:[^\.]{1,})\.(?:svg|html)$/', $file, $m)) {
                    $o[] = $base . $file;
                } else if (is_dir($base . $file)) {
                    $this->searchFiles($base . $file . DIRECTORY_SEPARATOR, $o);
                }
            }
        }
    }

    public function buildTemplates($rootDir, $tableId, $pathOffset = '') {
        $basePath = rtrim($rootDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $pathOffset = trim($pathOffset, DIRECTORY_SEPARATOR);
        mb_strlen($pathOffset, 'UTF-8') ? false : $pathOffset = null;
        $pathOffset ? $basePath.=$pathOffset . DIRECTORY_SEPARATOR : false;
        $files = [];
        $this->searchFiles($basePath, $files);
        $basePath = str_ireplace(["\\", '/'], ".", $basePath);
        $out = [];
        foreach ($files as $file) {
            $lname = str_ireplace(["\\", "/"], ".", $file);
            $lname = trim(str_ireplace($basePath, "", $lname), '.');
            $m = [];
            if (preg_match('/^(?P<pn>.{1,})\.(?:html|svg)$/i', $lname, $m)) {
                $out[$m['pn']] = file_get_contents($file);
            }
        }
        $encoded = json_encode($out, JSON_FORCE_OBJECT);
        return "*/window.Eve.ADVTable.TemplateManager.LocalTemplateManager(\"{$tableId}\",{$encoded});/*";
    }

    public function buildTemplatesRet($rootDir, $rn = "TPLS", $pathOffset = '') {
        $basePath = rtrim($rootDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $pathOffset = trim($pathOffset, DIRECTORY_SEPARATOR);
        mb_strlen($pathOffset, 'UTF-8') ? false : $pathOffset = null;
        $pathOffset ? $basePath.=$pathOffset . DIRECTORY_SEPARATOR : false;
        $files = [];
        $this->searchFiles($basePath, $files);
        $basePath = str_ireplace(["\\", '/'], ".", $basePath);
        $out = [];
        foreach ($files as $file) {
            $lname = str_ireplace(["\\", "/"], ".", $file);
            $lname = trim(str_ireplace($basePath, "", $lname), '.');
            $m = [];
            if (preg_match('/^(?P<pn>.{1,})\.(?:html|svg)$/i', $lname, $m)) {
                $out[$m['pn']] = file_get_contents($file);
            }
        }
        $encoded = json_encode($out, JSON_FORCE_OBJECT);
        return "*/{$rn} = {$encoded};/*";
    }

    /**
     * 
     * @return \Static
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

}
