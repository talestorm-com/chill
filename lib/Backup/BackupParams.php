<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup;

class BackupParams implements \Backup\IBackupParams {

    use \DataMap\TInternalDataMapProxy;

    /** @var \DataMap\IDataMap */
    protected $data;

    protected function t_array_data_map_get_internal_map(): \DataMap\IDataMap {
        return $this->data;
    }

    protected function __construct(string $config_name) {
        $this->data = \DataMap\CommonDataMap::F();
        $config_dir = \Config\Config::F()->CONFIG_DIR . "backup_config";
        $ds = DIRECTORY_SEPARATOR;
        if (!(file_exists($config_dir) && is_dir($config_dir))) {
            @mkdir($config_dir, 0777, true);
        }
        if (!(file_exists($config_dir) && is_dir($config_dir) && is_readable($config_dir))) {
            BackupError::RF("config dir `%s` is unavailable", $config_dir);
        }
        $file_name = "{$config_dir}{$ds}{$config_name}.php";
        if (!file_exists($file_name) || !is_file($file_name) || !is_readable($file_name)) {
            BackupError::RF("config file `%s` is unavailable", $file_name);
        }
        $data = include $file_name;
        if (!is_array($data)) {
            BackupError::RF("config file `%s` is not valid config array", $file_name);
        }
        $this->data->rebind($data);
    }

    public static function F(string $config_name): IBackupParams {
        return new static($config_name);
    }

}
