<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets;

/**
 * @property string $text
 */
abstract class InlineAsset extends AbstractAsset {

    /** @var string */
    protected $text;

    protected function __get__text() {
        return $this->text;
    }

    public function get_asset_key(): string {
        return md5(trim($this->text));
    }

    public function __construct(string $asset_text, int $priority = 0) {
        $this->text = $asset_text;
        $this->priority = $priority;
    }

    public static function F(string $asset_text, int $priority = 0) {
        return new static($asset_text, $priority);
    }

}
