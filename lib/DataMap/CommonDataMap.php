<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

class CommonDataMap extends AbstractDataMap {

    protected function _data_map_read_only(): bool {
        return false;
    }

    protected function on_instance_created() {
        $re = [];
        $this->rebind($re);
    }

    protected static function _data_map_singleton(): bool {
        return false;
    }

    protected function _data_map_can_rebind(): bool {
        return true;
    }

}
