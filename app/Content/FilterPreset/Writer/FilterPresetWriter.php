<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\FilterPreset\Writer;

/**
 * Description of FilterPresetWriter
 *
 * @author eve
 * @property \DataMap\IDataMap $data_input
 * @property \DataMap\IDataMap $common_input
 * @property \DB\SQLTools\SQLBuilder $builder
 * @property string $temp_var
 * @property integer $result_id
 * @property string[] $messages
 * @property \DataMap\IDataMap $runtime 
 * 
 */
class FilterPresetWriter {

    use \common_accessors\TCommonAccess;

    protected static $part_writers = [
        CommonWriter::class,
        PropertiesWriter::class,
        ItemsWriter::class,
    ];
    protected static $post_writers = [
        ImagesWriter::class,
        ItemsImageUploader::class,
        ItemsCleaner::class,
        QtyWriter::class,
        CacheReset::class,
        Publisher::class,
    ];

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var \DataMap\IDataMap */
    protected $data_input;

    /** @var \DataMap\IDataMap */
    protected $common_input;

    /** @var \DataMap\IDataMap */
    protected $runtime;

    /** @var \DB\SQLTools\SQLBuilder */
    protected $builder;

    /** @var string */
    protected $temp_var;

    /** @var integer */
    protected $result_id;

    /** @var string[] */
    protected $messages;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return \DataMap\IDataMap */
    protected function __get__data_input() {
        return $this->data_input;
    }

    /** @return \DataMap\IDataMap */
    protected function __get__common_input() {
        return $this->common_input;
    }

    /** @return \DB\SQLTools\SQLBuilder */
    protected function __get__builder() {
        return $this->builder;
    }

    /** @return string */
    protected function __get__temp_var() {
        return $this->temp_var;
    }

    /** @return integer */
    protected function __get__result_id() {
        return $this->result_id;
    }

    /** @return string[] */
    protected function __get__messages() {
        return $this->messages;
    }

    protected function __get__runtime() {
        return $this->runtime;
    }

    //</editor-fold>





    public function __construct(\DataMap\IDataMap $data_input, \DataMap\IDataMap $common_input) {
        $this->data_input = $data_input;
        $this->common_input = $common_input;
        $this->builder = \DB\SQLTools\SQLBuilder::F();
        $this->temp_var = "@a" . md5(__METHOD__);
        $this->messages = [];
        $r = [];
        $this->runtime = \DataMap\CommonDataMap::F()->rebind($r);
    }

    /**
     * 
     * @param \DataMap\IDataMap $data_input
     * @param \DataMap\IDataMap $common_input
     * @return \Content\FilterPreset\Writer\FilterPresetWriter
     */
    public static function F(\DataMap\IDataMap $data_input, \DataMap\IDataMap $common_input): FilterPresetWriter {
        return new static($data_input, $common_input);
    }

    public function run(): int {
        foreach (static::$part_writers as $writer_class) {
            $writer_class::F()->run($this);
        }
        $this->result_id = $this->builder->execute_transact($this->temp_var);
        foreach (static::$post_writers as $writer_class) {
            try {
                $writer_class::F()->run($this);
            } catch (\Throwable $e) {
                $this->messages[] = $e->getMessage();
            }
        }
        return $this->result_id;
    }

    public function append_message(string $s) {
        $this->messages[] = $s;
    }

}
