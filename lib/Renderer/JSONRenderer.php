<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Renderer;

/**
 * JSONрендерер
 * методы render и render lauout различаются только заголовком
 */
class JSONRenderer extends Renderer {

    public function render() {
        \Errors\common_error::RF("NOT IMPLEMENTED %s",__METHOD__);// надо подумать
        $result = []; // нужно переформатировать результат - добавить опциональный статус и перенести все из секции default в корневую секцию
        
        die(json_encode($result));
    }

    public function render_layout() {
        if (!headers_sent()) {
            header("Content-Type: application/json", true);
        }
        $this->render();
    }

}
