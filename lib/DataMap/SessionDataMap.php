<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

class SessionDataMap extends AbstractDataMap {

    protected function _data_map_read_only(): bool {
        return false;
    }

    protected function on_instance_created() {
        $session_status = session_status();
        if ($session_status === PHP_SESSION_DISABLED) {
            DataMapError::RF("cant instantiate session - sessions is disabled");
        } else if ($session_status === PHP_SESSION_NONE) {
            session_start();
        }
        $this->rebind($_SESSION);
    }

    protected static function _data_map_singleton(): bool {
        return true;
    }

    protected function _data_map_can_rebind(): bool {
        return false;
    }

}
