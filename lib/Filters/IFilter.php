<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters;

interface IFilter {

    public function apply($input_value, IFilterParams $params = null);
}
