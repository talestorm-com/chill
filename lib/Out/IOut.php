<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out;

/**
 * разделяем данные и их рендеринг
 * коллектор данных - тут, а рендерер - отдельным классом (JSON,Html,XML - это же зависит от класса)
 */
interface IOut extends assets\IAssetManager, \common_accessors\IMarshall {

    public function add(string $key, $data, string $section = 'default'): IOut;

    public function remove_section(string $section): IOut;

    public function replace_section(string $section, array $value): IOut;

    public function remove(string $key, string $section = 'default'): IOut;

    public function get(string $key, string $section = 'default');
    
    public function getOpt(string $key,$default=null,$section='default');
    
    public function get_euid(string $x = null):string;
    
}
