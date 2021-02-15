<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Mutex;

interface IMutex {

    /**
     * accure mutex (wait if need)
     * @throws \Exception
     */
    public function get();

    /**
     * release mutex
     */
    public function release();

    /**
     * accure mutex if it is not buisy. else returns false
     * @return bool
     * @throws \Exception
     */
    public function get_if(): bool;
}
