<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ADVTable\Data;

class GPData extends AbstractMixedData {

    protected static $instance = null;

    protected function bind() {
        $this->p1 = GetData::F();
        $this->p2 = PostData::F();
    }

}
