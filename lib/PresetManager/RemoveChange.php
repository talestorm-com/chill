<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PresetManager;

/**
 * @property string $name
 */
class RemoveChange implements IChange {

    use \common_accessors\TCommonAccess;

    /** @var string $name */
    protected $name;

    protected function __get__name() {
        return $this->name;
    }

    public function sql(\DB\SQLTools\SQLBuilder $b) {
        $b->inc_counter();
        $b->push("DELETE FROM presets WHERE name=:P{$b->c}n;");
        $b->push_param(":P{$b->c}n", $this->name);
        $b->inc_counter();
    }

    public function __construct(string $name) {
        $this->name = $name;
    }

    /**
     * 
     * @param string $name
     * @return \PresetManager\IChange
     */
    public static function F(string $name): IChange {
        return new static($name);
    }

}
