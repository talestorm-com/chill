<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Renderer;

interface IRenderer {

    public function render_layout();

    public function render();

    public static function F(): IRenderer;
}
