<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Renderer;
/**
 * основной html рендерер
 * render_layout выводит указанный в контроллере лэйаут + обвес ассетов
 * режим обвеса выходного мода?
 8 тоже подумать
 * линейные хендлеры?
 */
class HTMLRenderer extends Renderer {
    
    public function render() {// все данные должны быть в DataOut
        //контроллеры могут дополнительно выдавать события? - нафиг, себе дороже
        // модуль сначала устанавливает данные в jut. а потом вызывает свой шаблон
        // нужен сабрендер
        //и параметры - шаблон, модуль,идентификатор
        
    }

    public function render_layout() {
        // лэйаут должен приходить от контроллера как параметр, а не как линк?
        //нужен ли вообще рендерер как класс? просто игнорить?
        //и прямой вывод из контроллера?
        //и для датастрима тогда нужны будут только трансформеры, а не рендереры?
        //проверка - если вывод разрешен - вывод иначе - дамп в out? можно и html-parts
        //подумать
    }

}
