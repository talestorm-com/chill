<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Renderer;

abstract class Renderer implements IRenderer {

// рендерер создается контроллером только 1 раз - все остальные вызовы фабрики должны возвращать тот же рендерер
    /** @var IRenderer */
    protected static $instance;

    private final function __construct() {
        ;
    }

    public static final function F(): IRenderer {
        if (!static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

}
