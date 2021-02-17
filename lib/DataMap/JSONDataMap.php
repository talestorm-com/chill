<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

/**
 * Description of JSONDataMap
 *
 * @author eve
 */
class JSONDataMap extends AbstractDataMap {

    protected function _data_map_can_rebind(): bool {
        return false;
    }

    protected function _data_map_read_only(): bool {
        return false;
    }

    protected function on_instance_created() {
        try {
            $json_data = @file_get_contents("php://input");
            
            if ($json_data && is_string($json_data) && mb_strlen($json_data,'UTF-8')) {
                $json_array = json_decode($json_data, true);                
                if (is_array($json_array)) {
                    $this->rebind($json_array);
                    $this->failsafe_data = &$_POST;
                    return;
                }
            }
        } catch (\Throwable $e) {
            
        }
        $r = [];
        $this->rebind($r);
    }

    protected static function _data_map_singleton(): bool {
        return true;
    }

}
