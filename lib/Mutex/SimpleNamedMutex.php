<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Mutex;

class SimpleNamedMutex extends AbstractMutex {

    protected function prepare_mutex_name($file_spec): string {
        $fn = \Helpers\Helpers::NEString($file_spec, null);
        return $fn ? md5($fn) : MutexError::R("invalid mutex name");
    }

}
