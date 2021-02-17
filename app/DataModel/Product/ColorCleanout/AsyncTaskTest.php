<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\ColorCleanout;

class AsyncTaskTest extends \AsyncTask\AsyncTaskAbstract {

    protected function exec() {
        $this->log("starting sleep 30 sec");
        sleep(30);        
        $this->log("stop sleep 30 sec");
    }

}
