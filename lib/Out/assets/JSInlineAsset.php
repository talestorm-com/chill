<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets;

class JSInlineAsset extends InlineAsset {

    public function get_asset_template(): string {
        return 'script_inline';
    }

}
