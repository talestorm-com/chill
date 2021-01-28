<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets;

interface IAssetManager {

    public function add_script(string $url, int $priority = 0, bool $async = true): IAssetManager;

    public function add_css(string $url, int $priority = 0): IAssetManager;

    public function add_inline_js(string $js, int $priority = 0): IAssetManager;

    public function add_inline_css(string $css, int $priority = 0): IAssetManager;
    
    public function sort_assets():IAssetManager;
}
