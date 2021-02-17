<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets;

/**
 * @property int $priority
 * @property string $asset_key
 * @property string $template
 */
abstract class AbstractAsset implements IAsset {

    use \common_accessors\TCommonAccess;

    /** @var int */
    protected $priority = 0;

    protected function __get__priority() {
        return $this->get_priority();
    }

    protected function __get__asset_key() {
        return $this->get_asset_key();
    }

    protected function __get__template() {
        return $this->get_asset_template();
    }

    abstract public function get_asset_key(): string;

    abstract public function get_asset_template(): string;

    public function get_priority(): int {
        return $this->priority;
    }
   
}
