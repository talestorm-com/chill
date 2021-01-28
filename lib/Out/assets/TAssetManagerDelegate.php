<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets;

trait TAssetManagerDelegate {

    protected function t_asset_manager_delegate_get_internal_object(): IAssetManager {
        return $this->asset_manager;
    }

    public function add_script(string $url, int $priority = 0, bool $async = true): IAssetManager {
        return $this->t_asset_manager_delegate_get_internal_object()->add_script($url, $priority, $async);
    }

    public function add_css(string $url, int $priority = 0): IAssetManager {
        return $this->t_asset_manager_delegate_get_internal_object()->add_css($url, $priority);
    }

    public function add_inline_js(string $js, int $priority = 0): IAssetManager {
        return $this->t_asset_manager_delegate_get_internal_object()->add_inline_js($js, $priority);
    }

    public function add_inline_css(string $css, int $priority = 0): IAssetManager {
        return $this->t_asset_manager_delegate_get_internal_object()->add_inline_css($css, $priority);
    }

    public function sort_assets(): IAssetManager {
        return $this->t_asset_manager_delegate_get_internal_object()->sort_assets();
    }
    
    

}
