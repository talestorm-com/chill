<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets;

/**
 * @property string $url
 * @property bool $async
 */
class JSAsset extends AbstractAsset {

    /** @var bool */
    protected $async = true;

    /** @var string */
    protected $url = null;

    protected function __get__url() {
        return \Helpers\Helpers::add_params_to_url($this->url, ['v' => VersionMonitor::F()->version_key]);
    }

    protected function __get__async() {
        return $this->async;
    }

    public function __construct(string $url, int $priority = 0, bool $async = true) {
        $this->url = $url;
        $this->async = $async;
        $this->priority = $priority;
        $this->url = preg_replace_callback("/<!PARAM\s{1,}(?P<pn>\S{1,})!>/i", function($matches) {
            return \PresetManager\PresetManager::F()->get_filtered($matches['pn'], ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']);
        }, $this->url);
    }

    /**
     * 
     * @param string $url
     * @param int $priority
     * @param bool $async
     * @return \static
     */
    public static function F(string $url, int $priority = 0, bool $async = true) {
        return new static($url, $priority, $async);
    }

    public function get_asset_key(): string {
        return md5(trim($this->url, "\\/"));
    }

    public function get_asset_template(): string {
        return 'script_link';
    }

}
