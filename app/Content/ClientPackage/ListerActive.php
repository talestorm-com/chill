<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ClientPackage;

/**
 * Description of ListerActive
 *
 * @author eve
 */
class ListerActive extends Lister {
    //put your code here
    
    protected function create_direct_conditions() {
        $this->filter->addDirectCondition("( active=1 )");
    }
}
