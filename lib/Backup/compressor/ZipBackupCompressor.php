<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup\compressor;

class ZipBackupCompressor implements \Backup\IBackupCompressor {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator;

    protected $items;

    /** @var \Backup\IBackupParams */
    protected $params;
    protected $ds;

    protected function __construct(\Backup\IBackupParams $params) {
        $this->params = $params;
        $this->ds = DIRECTORY_SEPARATOR;
    }

    public function get_output_files(): array {
        if (!is_array($this->items)) {
            \Backup\BackupError::R("accessing to compressed data before compress");
        }
        return $this->items;
    }

    protected function get_files_per_fork($total, &$files_per_fork, &$fork_qty) {
        $max_fork_qty = 10;
        $fork_qty = min([$max_fork_qty, ceil($total / 1000)]);
        $files_per_fork = ceil($total / $fork_qty);
    }

    protected function get_dir_name() {
        $tmp = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $name = "zip_backup_compressor_{$this->params->get_filtered("_int_backup_name", ["Strip", "Trim", "NEString", "DefaultEmptyString"])}";
        $base_dir = "{$tmp}{$name}";
        if (!file_exists($base_dir) || !is_dir($base_dir)) {
            @mkdir($base_dir, 0777, true);
        }
        if (!(file_exists($base_dir) && is_dir($base_dir) && is_readable($base_dir) && is_writable($base_dir))) {
            \Backup\BackupError::RF("compressor temp dir `%s` is unavailable", $base_dir);
        }
        $list = scandir($base_dir);
        foreach ($list as $old_file) {
            if (is_file("{$base_dir}{$this->ds}{$old_file}")) {
                @unlink("{$base_dir}{$this->ds}{$old_file}");
            }
        }
        return $base_dir;
    }

    public function run(\Backup\IBackupData $files_to_compress): \Backup\IBackupCompressor {
        $base_dir_name = $this->get_dir_name();
        $this->params->set("files_to_remove", array_merge([$base_dir_name], $this->params->get_filtered("files_to_remove", ["NEArray", "DefaultEmptyArray"])));
        $base_dir_name .= DIRECTORY_SEPARATOR;
        $base_file_name = "{$base_dir_name}zip_backup";
        $files = $files_to_compress->get_files_list();
        $this->items = [];
        //file_put_contents(__DIR__.DIRECTORY_SEPARATOR."dump", $files);
        if (\Backup\BackupForkDetector::F()->can_fork && count($files) > 1000) {
            $forks_key = [];
            $files_per_fork = 0;
            $forks = 10;
            $error_forks = 0;
            $this->get_files_per_fork(count($files), $files_per_fork, $forks);
            for ($i = 0; $i < $forks; $i++) {
                $files_for_fork = array_slice($files, $i * $files_per_fork, $files_per_fork);
                //file_put_contents(__DIR__.DIRECTORY_SEPARATOR."dumpx{$i}", $files_for_fork);
                if (count($files_for_fork)) {
                    $file_name = "{$base_file_name}_{$i}.zip";
                    $this->items[] = $file_name;
                    $pid = pcntl_fork();
                    if ($pid === -1) {
                        \Backup\BackupError::R("fork error");
                    } else if ($pid) {//parent
                        $forks_key["A{$pid}"] = $pid;
                    } else {//child   
                        try {
                            $this->run_thread($files_for_fork, $file_name);
                            die();
                        } catch (\Throwable $x) {
                            die(100);
                        }
                    }
                }
            }
            $stop = false;
            while (!$stop) {
                foreach ($forks_key as $key => $pid) {
                    $status = null;
                    $r = pcntl_waitpid($pid, $status, WNOHANG);
                    if ($r === -1 || $r > 0) {
                        unset($forks_key[$key]);
                    }
                    if (!pcntl_wifexited($status)) {
                        $error_forks++;
                    }
                }
                $stop = count($forks_key) ? false : true;
            }
            if ($error_forks) {
                \Backup\BackupError::RF("compressor fork error");
            }
        } else {
            $this->items[] = "{$base_file_name}.zip";
            $this->run_thread($files_to_compress->get_files_list(), "{$base_file_name}.zip");
        }
        return $this;
    }

    protected function run_thread(array $files, string $file_name) {
        $zip = new \ZipArchive();
        $zip->open($file_name, \ZipArchive::OVERWRITE || \ZipArchive::CREATE);
        foreach ($files as $zip_name => $file) {
            if (file_exists($file) && is_file($file)) {
                $zip->addFromString($zip_name, file_get_contents($file));
            }
        }
        $zip->close();
    }

    public static function F(\Backup\IBackupParams $params): \Backup\IBackupCompressor {
        return new static($params);
    }

    protected function t_iterator_get_internal_iterable_name() {
        if (!is_array($this->items)) {
            \Backup\BackupError::R("accessing to compressed data before compress");
        }
        return 'items';
    }

}
