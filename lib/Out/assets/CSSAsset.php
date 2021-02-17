<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets;

/**
 * @property string $url
 */
class CSSAsset extends AbstractAsset {

    /** @var string */
    protected $url;

    protected function __get__url() {
        return \Helpers\Helpers::add_params_to_url($this->url, ['v' => VersionMonitor::F()->version_key]);
    }

    public function __construct(string $url, int $priority = 0) {
        $this->url = $url;
        $this->priority = $priority;
    }

    /**
     * 
     * @param string $url
     * @param int $priority
     * @return \static
     */
    public static function F(string $url, int $priority = 0) {
        return new static($url, $priority);
    }

    public function get_asset_key(): string {
        return md5(trim($this->url, "\\/"));
    }

    public function get_asset_template(): string {
        return 'css_link';
    }

}
