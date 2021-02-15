<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup\transport;

class DummyBackupTransport implements \Backup\IBackupTransport {

    /** @var \Backup\IBackupParams */
    protected $params;

    protected function __construct(\Backup\IBackupParams $params) {
        $this->params = $params;
    }

    public function run(\Backup\IBackupCompressor $files): \Backup\IBackupTransport {
        //do nothing
        return $this;
    }

    public static function F(\Backup\IBackupParams $params): \Backup\IBackupTransport {
        return new static($params);
    }

}
