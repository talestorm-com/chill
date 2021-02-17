<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup\transport;

class FtpBackupTransport implements \Backup\IBackupTransport {

    /** @var \Backup\IBackupParams */
    protected $params;
    protected $host;
    protected $user;
    protected $password;
    protected $base_dir;
    protected $instance_dir_rule;
    protected $remote_dir_name;

    protected function __construct(\Backup\IBackupParams $params) {
        $this->params = $params;
        $this->host = $this->params->get_filtered("ftp_transport_host", ['Strip', 'Trim', 'NEString', "DefaultNull"]);
        $this->host ? 0 : \Backup\BackupError::R("FtpTransport requires host name");
        $this->user = $this->params->get_filtered("ftp_transport_user", ['Strip', 'Trim', 'NEString', "DefaultNull"]);
        $this->user ? 0 : \Backup\BackupError::R("FtpTransport requires user name");
        $this->password = $this->params->get_filtered("ftp_transport_password", ['Strip', 'Trim', 'NEString', "DefaultNull"]);
        $this->password ? 0 : \Backup\BackupError::R("FtpTransport requires password");
        $this->base_dir = $this->params->get_filtered("ftp_transport_base_dir", ["Strip", "Trim", "NEString", "DefaultNull"]);
        $this->base_dir ? 0 : \Backup\BackupError::R("FtpTransport requires base dir for daily backups");
        $this->instance_dir_rule = $this->params->get_filtered("ftp_transport_instance_dir_rule", ["Strip", "Trim", "NEString", "DefaultNull"]);
        $this->instance_dir_rule ? 0 : \Backup\BackupError::R("FtpTransport requires instance_dir_rule for daily backups");
        $d = new \DateTime();
        $this->remote_dir_name = $d->format($this->instance_dir_rule);
    }

    public function run(\Backup\IBackupCompressor $files): \Backup\IBackupTransport {
        if (count($files)) {
            $this->ftp_prepare_dir();
            if (count($files) > 1 && \Backup\BackupForkDetector::F()->can_fork) {
                $this->run_in_fork($files->get_output_files());
            } else {
                $this->run_single_thread($files->get_output_files());
            }
        }
        return $this;
    }

    protected function run_in_fork(array $files) {
        $files_per_forks = [];
        $fork_count = 10;
        $i = 0;
        foreach ($files as $file) {
            $i >= $fork_count ? $i = 0 : false;
            array_key_exists("F{$i}", $files_per_forks) ? 0 : $files_per_forks["F{$i}"] = [];
            $files_per_forks["F{$i}"][] = $file;
            $i++;
        }
        $forks = [];
        foreach ($files_per_forks as $fork_data) {
            if (count($fork_data)) {
                $pid = pcntl_fork();
                if ($pid === -1) {
                    \Backup\BackupError::R("fork error");
                } else if ($pid) {//parent
                    $forks["A{$pid}"] = $pid;
                } else {//child   
                    try {
                        $this->run_single_thread($fork_data);
                        die();
                    } catch (\Throwable $x) {
                        die(100);
                    }
                }
            }
        }
        $error_forks = 0;
        $stop = false;
        while (!$stop) {
            foreach ($forks as $key => $pid) {
                $status = null;
                $r = pcntl_waitpid($pid, $status, WNOHANG);
                if ($r === -1 || $r > 0) {
                    unset($forks[$key]);
                }
                if (!pcntl_wifexited($status)) {
                    $error_forks++;
                }
            }
            $stop = count($forks) ? false : true;
        }
        if ($error_forks) {
            \Backup\BackupError::RF("transport fork error");
        }
    }

    protected function run_single_thread(array $files) {
        $connection = null;
        try {
            $connection = $this->ftp_go_target();
            foreach ($files as $file) {
                if (file_exists($file) && is_file($file)) {
                    $frna = explode(DIRECTORY_SEPARATOR, $file);
                    $rfn = $frna[count($frna) - 1];
                    ftp_put($connection, $rfn, $file, FTP_BINARY);
                }
            }
            ftp_close($connection);
            $connection = null;
        } catch (\Throwable $e) {
            $connection ? ftp_close($connection) : 0;
            $connection = null;
            throw $e;
        }
    }

    protected function ftp_go_target() {
        $connection = ftp_connect($this->host);
        try {
            $connection ? 0 : \Backup\BackupError::RF("cant connect to host `%s`", $this->host);
            if (!ftp_login($connection, $this->user, $this->password)) {
                \Backup\BackupError::RF("cant login in `%s` as `%s`", $this->host, $this->user);
            }
            $dirs = trim(str_ireplace(["\\", "/"], DIRECTORY_SEPARATOR, $this->base_dir), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->remote_dir_name;
            $this->ftp_mksubdirs($connection, $dirs);
        } catch (\Throwable $e) {
            $connection ? ftp_close($connection) : 0;
            $connection = null;
            throw $e;
        }
        return $connection;
    }

    protected function ftp_prepare_dir() {
        $connection = null;
        try {
            $connection = $this->ftp_go_target();
            $this->ftp_clean_dir_current($connection);
            ftp_close($connection);
        } catch (\Throwable $e) {
            if ($connection) {
                ftp_close($connection);
            }
            throw $e;
        }
    }

    protected function ftp_clean_dir_current($connection) {
        $files = ftp_nlist($connection, ".");
        foreach ($files as $file) {
            if (mb_substr($file, 0, 1, 'UTF-8') !== '.') {
                @ftp_delete($connection, $file);
            }
        }
    }

    protected function ftp_mksubdirs($ftpcon, $ftpath) {
        $parts = explode('/', $ftpath); // 2013/06/11/username
        foreach ($parts as $part) {
            if (!@ftp_chdir($ftpcon, $part)) {
                ftp_mkdir($ftpcon, $part);
                ftp_chdir($ftpcon, $part);
            }
        }
    }

    public static function F(\Backup\IBackupParams $params): \Backup\IBackupTransport {
        return new static($params);
    }

}
