<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets\minifiers;

final class AssetMinifier {

    /** @var AssetMinifier */
    protected static $instance;

    protected function __construct() {
        static::$instance = $this;
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'CSSmin.php';
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'JSMinPlus.php';
    }

    public function minify_js(string $in): string {
        return \JSMinPlus::minify($in);
    }

    public function minify_css(string $in): string {
        return (new \CSSmin())->run($in);
    }

    /**
     * 
     * @return \Out\assets\minifiers\AssetMinifier
     */
    public static function F(): AssetMinifier {
        return static::$instance ? static::$instance : new static();
    }

}
