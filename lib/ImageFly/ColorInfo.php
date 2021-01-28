<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

class ColorInfo extends ImageInfo {

    protected function __get__valid() {
        return $this->image ? true : false;
    }

    protected function __get__properties() {
        return null;
    }

    protected function t_common_import_after_import() {
        $this->context = "_color";
        $this->title = "";
        $this->owner_id = "100";
        $this->properties = null;
    }

}
