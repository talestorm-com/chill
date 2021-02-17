<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Data;

interface IData extends \ArrayAccess {

    public function exists($n);

    public function get($n, $def = null);

    public function put($n, $v);

    public function remove($n);

    public function getPath($path, $def = null);

    public function pathExists($path);
}
