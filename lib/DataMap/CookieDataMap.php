<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

class CookieDataMap extends AbstractDataMap {

    protected function _data_map_read_only(): bool {
        return false;
    }

    protected function on_instance_created() {
        $this->rebind($_COOKIE);
    }

    protected static function _data_map_singleton(): bool {
        return TRUE;
    }

    public function set(string $key, $value): IDataMap {
        if (!headers_sent()) {
            setcookie($key, $value, 0, "/", null, true, true);
        } else {
            DataMapError::RF("cant set cookie `%s` - headers alredy sent", $key);
        }
        return $this;
    }

    public function set_with_ttl(string $key, $value, int $ttl = 0): CookieDataMap {
        if (!headers_sent()) {
            setcookie($key, $value, time() + $ttl, "/", null, true, true);
        } else {
            DataMapError::RF("cant set cookie `%s` - headers alredy sent", $key);
        }
        return $this;
    }

    public function remove(string $key): IDataMap {
        if (!headers_sent()) {
            setcookie($key, "", time() - 100000, "/");
        } else {
            DataMapError::RF("cant set expired cookie `%s` - headers alredy sent", $key);
        }
        return $this;
    }

    protected function _data_map_can_rebind(): bool {
        return false;
    }

}
