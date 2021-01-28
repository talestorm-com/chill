<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup\data;

class DatabaseBackupData implements \Backup\IBackupData {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator;

    protected $items;

    /** @var \Backup\IBackupParams */
    protected $params;

    protected function __construct(\Backup\IBackupParams $params) {
        $this->params = $params;
    }

    public function get_files_list(): array {
        if (is_array($this->items)) {
            return $this->items;
        }
        \Backup\BackupError::R("file list requests before its filled");
    }

    protected function t_iterator_get_internal_iterable_name() {
        if (is_array($this->items)) {
            return 'items';
        }
        \Backup\BackupError::R("file list requests before its filled");
    }

    public function run(): \Backup\IBackupData {
        $this->items = [];
        foreach (\Config\Config::F()->DB as $db_params) {/* @var $db_params \Config\DBConfig */
            $temp_name = tempnam(sys_get_temp_dir(), 'LARRO_DB');
            $this->params->set("files_to_remove", array_merge([$temp_name], $this->params->get_filtered("files_to_remove", ["NEArray", "DefaultEmptyArray"])));
            $ro = [];
            $ri = 0;
            exec("mysqldump -u {$db_params->user_name} -h {$db_params->server} -p{$db_params->password} -E -R --triggers {$db_params->db_name}  >{$temp_name}", $ro, $ri);
            $ri === 0 ? 0 : \Backup\BackupError::RF("error on database dump `%s`", $db_params->db_name);
            $this->items["{$db_params->db_name}_{$db_params->server}"] = $temp_name;
        }
        return $this;
    }

    public static function F(\Backup\IBackupParams $params): \Backup\IBackupData {
        return new static($params);
    }

}
