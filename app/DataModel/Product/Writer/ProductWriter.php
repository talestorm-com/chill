<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Writer;

class ProductWriter {

    /** @var \Out\IOut */
    protected $out;

    /** @var \DataMap\IDataMap */
    protected $input;

    /** @var \DataMap\CommonDataMap */
    protected $data;
    protected $writers = [
        MainWriter::class,
        StringsWriter::class,
        MetaWriter::class,
        CatalogsWriter::class,
        ColorsWriter::class,
        SizeWriter::class,
        CrossWriter::class,
            //PriceWriter::class, // прайс через эту штуку не обновлять
    ];
    protected $after_writers = [
        ImageParamsWriter::class,
    ];

    protected function __construct(\Out\IOut $out, \DataMap\IDataMap $input = null) {
        $this->out = $out;
        $this->input = $input ? $input : \DataMap\GPDataMap::F();
    }

    protected function read_input_data(): \DataMap\CommonDataMap {
        $d = $this->input->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $d ? false : \Errors\common_error::R("invalid request");
        return \DataMap\CommonDataMap::F()->rebind($d);
    }

    public function run(): int {
        $this->data = $this->read_input_data();
        $builder = \DB\SQLTools\SQLBuilder::F();
        $t_var = "@a" . md5(__METHOD__);
        foreach ($this->writers as $writer_class) {
            $w = $writer_class::F();
            $w->run($this->data, $builder, $t_var);
        }

        $builder->empty ? \Errors\common_error::R("no data to write") : false;
        $ret_var = $builder->execute_transact($t_var);
        foreach ($this->after_writers as $writer_class) {
            $w = $writer_class::F();
            $w->run($this->data, $builder, $ret_var);
        }
        \DataModel\Product\Model\ProductModel::RESET_CACHE();
        \DataModel\Product\ColorCleanout\ColorCleanout::mk_params()->run();
        return $ret_var;
    }

    public static function F(\Out\IOut $out, \DataMap\IDataMap $input = null): ProductWriter {
        return new static($out, $input);
    }

}
