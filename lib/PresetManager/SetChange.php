<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PresetManager;

/**
 * @property string $name
 * @property string|null $value
 */
class SetChange implements IChange {

    use \common_accessors\TCommonAccess;

    /** @var string */
    protected $name;

    /** @var string|null */
    protected $value;

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string|null */
    protected function __get__value() {
        return $this->value;
    }

    public function sql(\DB\SQLTools\SQLBuilder $b) {
        $b->inc_counter();
        $b->push("INSERT INTO presets (name,`value`) VALUES(:P{$b->c}n,:P{$b->c}v) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);");
        $b->push_param(":P{$b->c}n", $this->name)->push_param(":P{$b->c}v", $this->value);
        $b->inc_counter();
    }

    public function __construct(string $name, string $value = null) {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * 
     * @param string $name
     * @param string $value
     * @return \PresetManager\IChange
     */
    public static function F(string $name, string $value = null): IChange {
        return new static($name, $value);
    }

}
