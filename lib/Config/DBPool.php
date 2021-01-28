<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Config;

class DBPool implements \Iterator, \Countable, \ArrayAccess {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator,
        \common_accessors\TArrayAccess;

    /**  @var DBConfig[] */
    protected $items = [];

    protected function __construct(array $confs) {
        $this->load($confs);
    }

    protected function load(array $confs) {
        $this->items = [];
        foreach ($confs as $one_config) {
            if (is_array($one_config)) {
                $item = DBConfig::F($one_config);
                if ($item && $item->is_valid() && !array_key_exists($item->instance_name, $this->items)) {
                    $this->items[$item->instance_name] = $item;
                }
            }
        }
        if (!count($this->items)) {
            config_error::R("no valid database connections found");
        }
    }

    public function get_default() {
        if (!$this->offsetExists('default')) {
            return reset($this->items);
        } else {
            return $this->items['default'];
        }
    }

    public static function F(array $data): DBPool {
        return new static($data);
    }

}
