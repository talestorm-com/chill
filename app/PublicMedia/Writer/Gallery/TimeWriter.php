<?php

namespace PublicMedia\Writer\Gallery;

/**
 * Description of TextWriter
 *
 * @author eve
 */
class TimeWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * 
     * @return \PublicMedia\Writer\Gallery\TimeWriter
     */
    public static function F(): TimeWriter {
        return new static();
    }

    public function run(Writer $w) {
        $w->builder->push("INSERT INTO public__gallery__up (id,updated) VALUES({$w->temp_var},NOW()) ON DUPLICATE KEY UPDATE updated=NOW();");
    }

}
