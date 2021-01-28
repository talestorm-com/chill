<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\assets;

/**
 * common asset interface
 */
interface IAsset {

    /**
     * asset priority. assets loads in order of priority
     */
    public function get_priority(): int;
    

    /**
     * asset unique key, based on asset content
     * required to avoid assets duplicates
     */
    public function get_asset_key(): string;
    
    
    /**
     * returns asset template name
     */
    public function get_asset_template():string;
}
