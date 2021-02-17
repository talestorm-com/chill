<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup\data;

use \Backup\IBackupData as IBackupData,
    \Backup\IBackupParams as IBackupParams;

class FilesBackupData implements IBackupData {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator;

    /** @var string[] */
    protected $items;

    /** @var IBackupParams */
    protected $params;

    /** @var string */
    protected $filters;

    /** @var string */
    protected $ds;
    protected $root_dir_name;

    protected function __construct(IBackupParams $params) {
        $this->params = $params;
        $this->ds = DIRECTORY_SEPARATOR;
    }

    public function get_files_list(): array {
        if (is_array($this->items)) {
            return $this->items;
        }
        BackupError::R("file list requests before its filled");
    }

    protected function t_iterator_get_internal_iterable_name() {
        if (is_array($this->items)) {
            return 'items';
        }
        BackupError::R("file list requests before its filled");
    }

    public function run(): IBackupData {
        $root = $this->params->get_filtered("file_backup_root_dir", ['Strip', "Trim", 'NEString', 'DefaultNull']);
        if ($root && file_exists($root) && is_dir($root) && is_readable($root)) {
            $this->root_dir_name = $root;
            $this->filters = $this->params->get_filtered("file_backup_skip_filters", ["NEArray", "ArrayOfNEString", "DefaultEmptyArray"]);
            $found_files = [];
            $this->scan_dir($root, $found_files);
            $this->items = $found_files;
        } else {
            $this->items = [];
        }        
        return $this;
    }

    protected function scan_dir(string $root_dir, array &$output) {
        $root = rtrim($root_dir, DIRECTORY_SEPARATOR);
        if (file_exists($root) && is_dir($root) && is_readable($root)) {
            if (!file_exists("{$root}{$this->ds}ignore_on_backup")) {
                $files = scandir($root);
                foreach ($files as $file_name) {
                    if ($file_name === ".." || $file_name === ".") {
                        continue;
                    }
                    $test_path = "{$root}{$this->ds}{$file_name}";
                    if (is_dir($test_path) && is_readable($test_path)) {
                        $this->scan_dir($test_path, $output);
                    } else if (is_file($test_path) && is_readable($test_path)) {
                        if ($this->can_backup_file($test_path)) {
                            $name_path = trim(str_ireplace($this->root_dir_name, "", $test_path), DIRECTORY_SEPARATOR);
                            $output[$name_path] = $test_path;
                        }
                    }// для линков - потом отдельный скрипт, который их пересоздаст
                }
            } else {
                $test_path = "{$root}{$this->ds}ignore_on_backup";
                $name_path = trim(str_ireplace($this->root_dir_name, "", $test_path), DIRECTORY_SEPARATOR);
                $output[$name_path] = $test_path;
                $this->process_files_force($root, $output);
            }
        }
    }

    protected function process_files_force($root, array &$output) {
        $list = scandir($root);
        $li = array_combine($list, $list);
        if (array_key_exists(".htaccess", $li)) {
            $test_path = "{$root}{$this->ds}.htaccess";
            $name_path = trim(str_ireplace($this->root_dir_name, "", $test_path), DIRECTORY_SEPARATOR);
            $output[$name_path] = $test_path;
            //$output[] = "{$root}{$this->ds}.htaccess";
        }
    }

    protected function can_backup_file($file_path) {
        foreach ($this->filters as $filter) {
            if (@preg_match($filter, $file_path)) {
                return false;
            }
        }
        return true;
    }

    public static function F(IBackupParams $params): IBackupData {
        return new static($params);
    }

}
