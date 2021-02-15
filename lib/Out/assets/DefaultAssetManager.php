<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets;

class DefaultAssetManager implements \Countable, \Iterator, IAssetManager {

    use \common_accessors\TCommonAccess,
        \common_accessors\TIterator;

    private static $instance;

    /** @var IAsset[] */
    protected $assets;

    protected function t_iterator_get_internal_iterable_name() {
        return 'assets';
    }

    protected function __construct() {
        $this->assets = [];
        static::$instance = $this;
        $this->load_common_assets();
    }

    protected function load_common_assets_from_array(array $common_assets, int &$asset_counter) {
        if (array_key_exists('styles', $common_assets) && is_array($common_assets['styles']) && count($common_assets['styles'])) {
            foreach ($common_assets['styles'] as $style_url) {
                $this->add_css($style_url, $asset_counter);
                $asset_counter++;
            }
        }
        if (array_key_exists('scripts', $common_assets) && is_array($common_assets['scripts']) && count($common_assets['scripts'])) {
            foreach ($common_assets['scripts'] as $script_rec) {
                if (is_array($script_rec)) {
                    $this->add_script($script_rec[0], $asset_counter, $script_rec[1]);
                    $asset_counter++;
                } else if (is_string($script_rec)) {
                    $this->add_script($script_rec, $asset_counter, false);
                    $asset_counter++;
                }
            }
        }
        if (array_key_exists('inline_styles', $common_assets) && is_array($common_assets['inline_styles']) && count($common_assets['inline_styles'])) {
            foreach ($common_assets['inline_styles'] as $style_text) {
                if (is_string($style_text)) {
                    $this->add_inline_css($style_text, $asset_counter);
                    $asset_counter++;
                }
            }
        }
        if (array_key_exists('inline_scripts', $common_assets) && is_array($common_assets['inline_scripts']) && count($common_assets['inline_scripts'])) {
            foreach ($common_assets['inline_scripts'] as $script_text) {
                if (is_string($script_text)) {
                    $this->add_inline_js($script_text, $asset_counter);
                    $asset_counter++;
                }
            }
        }
    }

    protected function load_common_assets() {
        $common_assets = \Helpers\Helpers::safe_array(\Config\Config::F()->COMMON_ASSETS);
        $asset_counter = -1000000;
        $ns = \Router\Router::F()->namespace;
        if ($ns) {
            $ns_specific_assets = array_key_exists($ns, $common_assets) && is_array($common_assets[$ns]) ? $common_assets[$ns] : [];
            $this->load_common_assets_from_array($ns_specific_assets,$asset_counter);
        }

        $this->load_common_assets_from_array($common_assets,$asset_counter);
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

    protected function add_asset(IAsset $a): IAssetManager {
        if (!array_key_exists($a->get_asset_key(), $this->assets)) {
            $this->assets[$a->get_asset_key()] = $a;
        }
        return $this;
    }

    public function add_script(string $url, int $priority = 0, bool $async = true): IAssetManager {
        $asset = JSAsset::F($url, $priority, $async);
        return $this->add_asset($asset);
    }

    public function add_css(string $url, int $priority = 0): IAssetManager {
        $asset = CSSAsset::F($url, $priority);
        return $this->add_asset($asset);
    }

    public function add_inline_js(string $js, int $priority = 0): IAssetManager {
        $asset = JSInlineAsset::F($js, $priority);
        return $this->add_asset($asset);
    }

    public function add_inline_css(string $css, int $priority = 0): IAssetManager {
        $asset = CSSInlineAsset::F($css, $priority);
        return $this->add_asset($asset);
    }

    public function sort_assets(): IAssetManager {
        uasort($this->assets, function(IAsset $a, IAsset $b) {
            /* @var $a IAsset */
            /* @var $b IAsset */
            return $a->get_priority() - $b->get_priority();
        });
        return $this;
    }

}
