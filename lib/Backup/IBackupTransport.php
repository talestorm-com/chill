<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup;

interface IBackupTransport {

    public static function F(IBackupParams $params): IBackupTransport;

    public function run(IBackupCompressor $files): IBackupTransport;
}
