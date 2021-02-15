<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

class RegisterController extends \controllers\FrontEnd\AbstractFrontendController {

    public static function get_default_action() {
        return "register";
    }

    protected function actionRegister() {
        $this->render_view($this->get_requested_layout("front/empty_layout"), $this->get_requested_template('register_sp'));
    }
    
    

}
