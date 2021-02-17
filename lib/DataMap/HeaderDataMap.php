<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataMap;

class HeaderDataMap extends AbstractDataMap {

    protected function _data_map_can_rebind(): bool {
        return false;
    }

    protected function _data_map_read_only(): bool {
        return false;
    }

    protected function prepare_key(string $key): string {
        return mb_strtolower($key, 'UTF-8');
    }

    protected function on_instance_created() {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            $m = [];
            if (preg_match("/^HTTP_(?P<hdr>.*)$/i", $key, $m)) {
                $header_name = str_ireplace('_', '-', mb_strtolower($m['hdr'], 'UTF-8'));
                $headers[$header_name] = $value;
            }
        }
        foreach ($_SERVER as $key => $value) {
            $m = [];
            $header_name = str_ireplace('_', '-', mb_strtolower($key, 'UTF-8'));
            if (!array_key_exists($header_name, $headers)) {
                $headers[$header_name] = $value;
            }
        }
        $this->rebind($headers);
    }

    public function remove(string $key): IDataMap {
        header_remove($key);
        return $this;
    }

    public function set(string $key, $value): IDataMap {
        if (!headers_sent()) {
            header("{$key}: {$value}", true);
        } else {
            DataMapError::RF("catnt set header `%s` - headers alredy sent", $key);
        }
        return $this;
    }

    protected static function _data_map_singleton(): bool {
        return true;
    }

}
