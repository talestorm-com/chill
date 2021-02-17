<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of cTestController
 *
 * @author eve
 */
class cTestController extends AbstractFrontendController {

    protected function API_mk_csrf() {
        $this->out->add('cc', \Helpers\Helpers::csrf_mk('a1'));
        $this->out->add('cch1', \Helpers\Helpers::referrer_host());
        $this->out->add('cch2', \Router\Request::F()->host);
    }

    protected function API_check_csrf() {
        $check = \DataMap\InputDataMap::F()->get_filtered('cc', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $result = \Helpers\Helpers::csrf_check('a1', $check);
        $this->out->add('cs', $result);
    }

    protected function API_sdump() {
        \Errors\common_error::R("deprecated");
        var_dump(\DataMap\SessionDataMap::F());
        die();
    }

}
