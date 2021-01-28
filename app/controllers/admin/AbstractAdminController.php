<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * 
 * @property  \DataImport\notificator\ImportLogNotificator $import_notificator
 * 
 */
abstract class AbstractAdminController extends \controllers\abstract_controller {

    
    
    protected function check_access() {

        $result = parent::check_access() && \Auth\Auth::F()->is(\Auth\Roles\RoleEmployer::class);
        return $result;
    }
    
    protected function check_api_access(): bool {
        $result = parent::check_api_access() && \Auth\Auth::F()->is(\Auth\Roles\RoleEmployer::class);
        
        return $result;
    }

    protected function on_after_init() {
        $this->out->add_css("/assets/css/layouts/admin.css", -10);        
        return parent::on_after_init();
    }

}
