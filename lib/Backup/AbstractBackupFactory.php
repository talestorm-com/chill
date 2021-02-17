<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup;

class AbstractBackupFactory {

    protected static function get_root_dir() {
        return __DIR__ . DIRECTORY_SEPARATOR;
    }

    protected static final function get_compressor_dir() {
        return static::get_root_dir() . "compressor" . DIRECTORY_SEPARATOR;
    }

    protected static final function get_data_dir() {
        return static::get_root_dir() . "data" . DIRECTORY_SEPARATOR;
    }

    protected static final function get_transport_dir() {
        return static::get_root_dir() . "transport" . DIRECTORY_SEPARATOR;
    }

    public static function compressor_instance(string $compressor_name, IBackupParams $params): IBackupCompressor {
        $compressor_name = ucfirst(mb_strtolower($compressor_name, "UTF-8"));
        $ns = "\\" . trim(__NAMESPACE__, "\\/") . "\\compressor\\";
        $class_name = "{$ns}{$compressor_name}BackupCompressor";
        if (class_exists($class_name)) {
            if (\Helpers\Helpers::class_implements($class_name, IBackupCompressor::class)) {
                /* @var $class_name IBackupCompressor */
                return $class_name::F($params);
            }
        }
        BackupError::RF("class `%s` form backup compressor `%s` not found", $class_name, $compressor_name);
    }

    public static function transport_instance(string $transport_name, IBackupParams $params): IBackupTransport {
        $transport_name = ucfirst(mb_strtolower($transport_name, "UTF-8"));
        $ns = "\\" . trim(__NAMESPACE__, "\\/") . "\\transport\\";
        $class_name = "{$ns}{$transport_name}BackupTransport";
        if (class_exists($class_name)) {
            if (\Helpers\Helpers::class_implements($class_name, IBackupTransport::class)) {
                /* @var $class_name IBackupTransport */
                return $class_name::F($params);
            }
        }
        BackupError::RF("class `%s` form backup transport `%s` not found", $class_name, $transport_name);
    }

    public static function data_instance(string $data_name, IBackupParams $params) {
        $data_name = ucfirst(mb_strtolower($data_name, "UTF-8"));
        $ns = "\\" . trim(__NAMESPACE__, "\\/") . "\\data\\";
        $class_name = "{$ns}{$data_name}BackupData";
        if (class_exists($class_name)) {
            if (\Helpers\Helpers::class_implements($class_name, IBackupData::class)) {
                /* @var $class_name IBackupdata */
                return $class_name::F($params);
            }
        }
        BackupError::RF("class `%s` form backup data `%s` not found", $class_name, $data_name);
    }

}
