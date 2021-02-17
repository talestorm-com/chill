<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DB\isolation;

interface IIsolationLevel{

    const LEVEL_READ_UNCOMMITED = 'READ UNCOMMITTED';
    const LEVEL_READ_COMMITED = 'READ COMMTITED';
    const LEVEL_REPEATABLE_READ = 'REPEATABLE READ';
    const LEVEL_SERIALIZABLE = 'SERIALIZABLE';
    const LEVEL_DEFAULT = static::LEVEL_REPEATABLE_READ;

    public function get_value(): string;
}
