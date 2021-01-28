<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

abstract class AbstractDefaultFilter extends AbstractFilter {

    protected function is_default_filter():bool {
        return true;
    }

}
