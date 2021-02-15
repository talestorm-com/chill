<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

/**
 * Description of InputDataMap
 *
 * @author eve
 */
class InputDataMap extends AbstractDataMap {

    protected function _data_map_can_rebind(): bool {
        return false;
    }

    protected function _data_map_read_only(): bool {
        return false;
    }

    protected function on_instance_created() {        
        
        if ( strcasecmp(HeaderDataMap::F()->get_filtered("Content-Type", ["Strip", "Trim", "NEString", "DefaultEmptyString"]), "application/json")===0) {
            $rv = JSONDataMap::F()->get_all_cloned();            
            $this->rebind($rv);
            $this->failsafe_data = &$_GET;
        } else {
            $this->rebind($_POST);
            $this->failsafe_data = &$_GET;
        }
    }

    protected static function _data_map_singleton(): bool {
        return true;
    }

}
