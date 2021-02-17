<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup;

/**
 * @property IBackupCompressor $compressor
 * @property IBackupData $data
 * @property IBackupParams $params
 * @property IBackupTransport $transport
 */
class AbstractBackupTask extends \AsyncTask\AsyncTaskAbstract implements \Backup\IBackupTask {

    use \common_accessors\TCommonAccess;

    //<editor-fold defaultstate="collapsed" desc="pros">
    /** @var IBackupCompressor */
    protected $compressor;

    /** @var IBackupData */
    protected $data;

    /** @var IBackupParams */
    protected $params;

    /** @var IBackupTransport */
    protected $transport;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return IBackupCompressor */
    protected function __get__compressor() {
        return $this->compressor;
    }

    /** @return IBackupData */
    protected function __get__data() {
        return $this->data;
    }

    /** @return IBackupParams */
    protected function __get__params() {
        return $this->params;
    }

    /** @return IBackupTransport */
    protected function __get__transport() {
        return $this->transport;
    }

    //</editor-fold>


    protected function get_log_file_name() {
        return "backup";
    }

    protected function exec() {
        try {
            $this->log("backup started");
            $params_name = $this->params->get_filtered("config", ["Strip", "Trim", "NEString", "DefaultNull"]);
            $params_name ? 0 : BackupError::R("backup task requires config name");
            $this->log("executing backup task `{$params_name}`");
            $this->params = BackupParams::F($params_name);
            $this->params->set("_int_backup_name", $params_name);
            $this->params->set("_int_root_dir_name", md5(__METHOD__)); //fake
            $compressor_name = $this->params->get_filtered("compressor", ["Strip", "Trim", "NEString", "DefaultNull"]);
            $compressor_name ? 0 : BackupError::R("backup task requires compressor name");
            $this->compressor = BackupFactory::compressor_instance($compressor_name, $this->params);
            $transport_name = $this->params->get_filtered("transport", ["Strip", "Trim", "NEString", "DefaultNull"]);
            $transport_name ? 0 : BackupError::R("backup task requires transport name");
            $this->transport = BackupFactory::transport_instance($transport_name, $this->params);
            $data_name = $this->params->get_filtered("data", ["Strip", "Trim", "NEString", "DefaultNull"]);
            $data_name ? 0 : BackupError::R("backup task requires data name");
            $this->data = BackupFactory::data_instance($data_name, $this->params);
            $this->run();
            $this->log("backup complete");
        } catch (\Exception $e) {
            $this->log(sprintf("%s at %s in %s", $e->getMessage(), $e->getLine(), $e->getFile()), "error");
            throw $e;
        }
        return $this;
    }

    public function get_backup_compressor(): IBackupCompressor {
        return $this->compressor;
    }

    public function get_backup_data(): IBackupData {
        return $this->data;
    }

    public function get_backup_params(): IBackupParams {
        return $this->params;
    }

    public function get_backup_transport(): IBackupTransport {
        return $this->transport;
    }

    public function run(): IBackupTask {
        $this->get_backup_data()->run();
        if (count($this->get_backup_data())) {
            $this->log(sprintf("found %s files.compressing", count($this->get_backup_data())));
            $this->get_backup_compressor()->run($this->get_backup_data());
            $this->log(sprintf("compressor output contains %s files.transporting", count($this->get_backup_compressor())));
            if (count($this->get_backup_compressor())) {
                $this->get_backup_transport()->run($this->get_backup_compressor());
                $this->log("transport completed. cleaning");
                $this->clean();
                $this->log("cleaning completed. finishing");
            } else {
                $this->log("compressor out is empty.nothing to transport");
            }
        } else {
            $this->log("no data to backup");
        }
        return $this;
    }

    protected function clean() {
        $files_to_remove = $this->params->get_filtered("files_to_remove", ["NEArray", "ArrayOfNEString", "DefaultEmptyArray"]);
        if (count($files_to_remove)) {
            foreach ($files_to_remove as $file) {
                $file = rtrim($file, DIRECTORY_SEPARATOR);
                if (file_exists($file) && is_readable($file) && is_writable($file)) {
                    if (is_dir($file)) {
                        $this->remove_dir_recursive($file);
                    } else if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
        }
    }

    protected function remove_dir_recursive(string $root) {
        $root = rtrim($root, DIRECTORY_SEPARATOR);
        if (file_exists($root) && is_dir($root) && is_writable($root) && is_readable($root)) {
            $list = scandir($root);
            foreach ($list as $file) {
                if ($file !== '.' && $file !== '..') {
                    if (mb_substr($file, 0, 1, 'UTF-8') !== '.') {
                        $full_path = $root . DIRECTORY_SEPARATOR . $file;
                        if (file_exists($full_path) && is_readable($full_path) && is_writable($full_path)) {
                            if (is_dir($full_path)) {
                                $this->remove_dir_recursive($full_path);
                            } else if (is_file($full_path)) {
                                @unlink($full_path);
                            }
                        }
                    }
                }
            }
            rmdir($root);
        }
    }

}
