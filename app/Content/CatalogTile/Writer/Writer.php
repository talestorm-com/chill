<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile\Writer;

class Writer {

    /** @var \Out\IOut */
    protected $out;

    /** @var \DataMap\IDataMap */
    protected $input;

    /** @var \DataMap\CommonDataMap */
    protected $data;
    protected $writers = [
        MainWriter::class,
        CatalogsWriter::class,
        PropertyWriter::class,
    ];
    protected $after_writers = [
    ];

    protected function __construct(\Out\IOut $out, \DataMap\IDataMap $input = null) {
        $this->out = $out;
        $this->input = $input ? $input : \DataMap\GPDataMap::F();
    }

    public function run(): int {
        $this->data = $this->input;
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
        \Content\CatalogTile\CatalogTile::clear_dependency_beacon();
        return $ret_var;
    }

    /**
     * 
     * @param \Out\IOut $out
     * @param \DataMap\IDataMap $input
     * @return \Content\CatalogTile\Writer\Writer
     */
    public static function F(\Out\IOut $out, \DataMap\IDataMap $input = null): Writer {
        return new static($out, $input);
    }

}
