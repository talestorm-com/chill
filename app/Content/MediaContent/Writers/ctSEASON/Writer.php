<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctSEASON;

/**
 * Description of Writer
 *
 * @author eve

 */
class Writer extends \Content\MediaContent\Writers\AWriter {

    private static $parts = [
        CommonDataWriter::class,
        SeasonDataWriter::class,
        SeasonDataStringsWriter::class,
        \Content\MediaContent\Writers\PropertiesWriter::class,
        \Content\MediaContent\Writers\GenreListWriter::class,
        \Content\MediaContent\Writers\TagListWriter::class,
        \Content\MediaContent\Writers\CountriesWriter::class,
        \Content\MediaContent\Writers\StudiosWriter::class,
        \Content\MediaContent\Writers\PersonalWriter::class,
        \Content\MediaContent\Writers\MetaWriter::class,
        PreplayWriter::class,
        LentViewWriter::class,
        
    ];
    private static $parts_after = [
        \Content\MediaContent\Writers\Ranker::class,
        lent_image_uploder::class,
        lent_gif_processor::class,
        lent_gif_uploader::class,
        
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
