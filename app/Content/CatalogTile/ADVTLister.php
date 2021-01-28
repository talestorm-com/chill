<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile;

class ADVTLister {

    /** @var \Out\IOut */
    protected $out;

    /** @var \DB\IDBAdapter */
    protected $adapter;

    /** @var \ADVTable\Data\IData */
    protected $input;

    /**
     * 
     * @param \Out\IOut $out
     * @param \DB\IDBAdapter $adapter
     * @param \ADVTable\Data\IData $input
     */
    public function __construct(\Out\IOut $out, \DB\IDBAdapter $adapter = null, \ADVTable\Data\IData $input = null) {
        $this->out = $out;
        $this->input = $input;
        $this->adapter = $adapter ? $adapter : \DB\DB::F();
    }

    protected function get_filters() {
        return [
            'id' => 'Int:id',
            'alias' => 'String:alias',
            'info' => 'String:info',
            'visible'=>'Int:visible',
        ];
    }

    protected function get_sorts() {
        return [
            'id' => 'id',
            'alias' => 'alias|id',
            'info' => 'info|id',
            'visible'=>'visible|id',
        ];
    }

    public function run() {
        $condition = \ADVTable\Filter\FixedTokenFilter::F($this->input, $this->get_filters());
        $direction = \ADVTable\Sort\FixedTokenSort::F($this->input, $this->get_sorts());
        $direction->tokens_separator = "|";
        $limitation = \ADVTable\Limit\FixedTokenLimit::F($this->input);
        $qt = "SELECT SQL_CALC_FOUND_ROWS id,alias,info,visible FROM catalog__tile %s %s %s %s;";
        $c = 0;
        $p = [];
        $where = $condition->buildSQL($p, $c);
        $items = $this->adapter->queryAll(sprintf($qt, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit), $p);
        $total = $this->adapter->get_found_rows();
        if ($total && !(count($items)) && $limitation->page > 0) {
            $limitation->page = 0;
            $items = $this->adapter->queryAll(sprintf($qt, $condition->whereWord, $where, $direction->SQL, $limitation->MySqlLimit), $p);
            $total = $this->adapter->get_found_rows();
        }
        $this->out->add('items', $items)->add('total', $total)->add('page', $limitation->page)->add('perpage', $limitation->perpage);
    }

    /**
     * 
     * 
     * @param \Out\IOut $out
     * @param \DB\IDBAdapter $adapter
     * @param \ADVTable\Data\IData $input
     * @return \Content\Slider\ADVTLister
     */
    public static function F(\Out\IOut $out, \DB\IDBAdapter $adapter = null, \ADVTable\Data\IData $input = null): ADVTLister {
        return new static($out, $adapter, $input);
    }

}
