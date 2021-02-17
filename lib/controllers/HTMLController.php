<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers;

abstract class HTMLController extends abstract_controller {
    
    protected function init_renderer() {
        /**
         * от рендерера по всей видимости отойдем - 
         * тогда контроллеру и модулям нужны идентификаторы cache_id -проще всего из неймспейса
         * 
         */
    }

}
