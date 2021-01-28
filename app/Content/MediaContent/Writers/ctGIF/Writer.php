<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctGIF;

/**
 * Description of Writer
 *
 * @author eve

 */
class Writer extends \Content\MediaContent\Writers\AWriter {

    private static $parts_before = [
        CDNCleaner::class,
    ];
    private static $parts = [
        CommonWriter::class,
        DataWriter::class,
        StringsWriter::class,
        \Content\MediaContent\Writers\PropertiesWriter::class,
        \Content\MediaContent\Writers\MetaWriter::class,
        \Content\MediaContent\Writers\TagListWriter::class,
        \Content\MediaContent\Writers\GenreListWriter::class,        
        \Content\MediaContent\Writers\CountriesWriter::class,
        
    ];
    private static $parts_after = [
        ImageUploader::class,
        ImageProcessor::class,
    ];

    /**
     * 
     * @return $this
     */
    public function run() {
        
        foreach (static::$parts_before as $part_class) {
            $part_class::F()->run($this);
        }
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
