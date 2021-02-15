<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup;

/**
 * Обеспечивает сжатие даных 
 * на выходе - имена временных файлов для отправки на сервер
 */
interface IBackupCompressor extends \Countable, \Iterator {

    public static function F(IBackupParams $params): IBackupCompressor;

    /**
     * 
     * запуск сжатия. 
     * @param \Backup\IBackupData $files_to_compress
     * @return IBackupCompressor
     */
    public function run(IBackupData $files_to_compress): IBackupCompressor;

    /**
     * имена архивов (может быть несколько)
     * @return string[]
     */
    public function get_output_files(): array;
}
