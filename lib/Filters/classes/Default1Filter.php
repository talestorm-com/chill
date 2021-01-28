<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

class Default1Filter extends \Filters\AbstractDefaultFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        return 1;
    }

}
