<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backup;

/**
 * обеспечивает подготовку списка файлов для резервного копирования
 * <b>только подготовку списка</b>, сжатие и тд - на других
 */
interface IBackupData extends \Countable, \Iterator {

    /**
     * фабрика
     * @param \Backup\IBackupParams $params
     * @return IBackupData
     */
    public static function F(IBackupParams $params): IBackupData;

    /**
     * запуск
     * @return IBackupData
     */
    public function run(): IBackupData;

    /**
     * список файлов подлежащих обработке
     * @return string[]
     */
    public function get_files_list(): array;
}
