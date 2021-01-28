<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup;

interface IBackupTask {

    public function get_backup_data(): IBackupData;

    public function get_backup_transport(): IBackupTransport;

    public function get_backup_params(): IBackupParams;

    public function get_backup_compressor(): IBackupCompressor;

    public function run(): IBackupTask;
}
