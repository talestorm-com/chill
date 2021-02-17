<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class InfoController extends AbstractAdminController {

    protected function API_get_product_groups() {
        $this->out->add('tree', \CatalogTree\CatalogTreeSinglet::F()->tree->marshall());
    }

}
