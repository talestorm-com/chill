<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Gallery;

/**
 * Description of TextWriter
 *
 * @author eve
 */
class TextWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * 
     * @return \PublicMedia\Writer\Gallery\TextWriter
     */
    public static function F(): TextWriter {
        return new static();
    }

    public function run(Writer $w) {
        if (\DataMap\InputDataMap::F()->exists("info")) {
            $info = \DataMap\InputDataMap::F()->get_filtered("info", ["Trim", "NEString", 'DefaultEmptyString']);
            $w->builder->push("INSERT INTO public__gallery__text (id,info) VALUES({$w->temp_var},:P{$w->builder->c}info) ON DUPLICATE KEY UPDATE info=VALUES(info);");
            $w->builder->push_param(":P{$w->builder->c}info", $info);
            $w->builder->inc_counter();
        }
    }

}
