<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctTEXT;

/**
 * Description of Writer
 *
 * @author eve

 */
class Writer extends \Content\MediaContent\Writers\AWriter {

    private static $parts = [
        CommonWriter::class,
        DataWriter::class,
        StringsWriter::class,
        \Content\MediaContent\Writers\PropertiesWriter::class,
        \Content\MediaContent\Writers\TagListWriter::class,
        \Content\MediaContent\Writers\MetaWriter::class,
    ];
    private static $parts_after = [
    ];

    /**
     * 
     * @return $this
     */
    public function run() {

        foreach (static::$parts as $part_class) {
            $part_class::F()->run($this);
        }
        $this->result_id = $this->builder->execute_transact($this->temp_var);
        foreach (static::$parts_after as $part_class) {
            $part_class::F()->run($this);
        }
        return $this;
    }

}
