<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class IndexController extends \controllers\admin\AbstractAdminController {

    protected function actionIndex() {
        $this->render_view('admin', 'index');
    }
    
    
   

}
