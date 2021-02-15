<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctCOLLECTION;

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
        ItemsWriter::class,
        PreplayWriter::class,
        LentViewWriter::class,
        PageViewWriter::class,
    ];
    private static $parts_after = [
        lent_image_uploder::class,
        lent_gif_processor::class,
        lent_gif_uploader::class,
        page_image_uploader::class,
        page_gif_processor::class,
        page_gif_uploader::class,
        \Content\MediaContent\Writers\Ranker::class,
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
