<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\FilterPreset;

/**
 * Description of Lister
 *
 * @author eve
 * @property \DataMap\InputDataMap $input
 * @property \ADVTable\Data\IData $bridge
 * @property \ADVTable\Filter\FixedTokenFilter $filter
 * @property \ADVTable\Limit\FixedTokenLimit $limit
 * @property \ADVTable\Sort\FixedTokenSort $order
 */
class Lister {

    //put your code here



    public function __construct(\DataMap\IDataMap $input = null) {
        $this->input = $input ? $input : \DataMap\InputDataMap::F();
        $this->bridge = \DataMap\ADVTIDataBridge::F($this->input);
        $this->filter = \ADVTable\Filter\FixedTokenFilter::F($this->bridge, $this->get_filters());
        $this->limit = \ADVTable\Limit\FixedTokenLimit::F($this->bridge);
        $this->order = \ADVTable\Sort\FixedTokenSort::F($this->bridge, $this->get_sorts());
        $this->order->tokens_separator = '|';
    }

    protected function get_query_proto() {
        return "SELECT SQL_CALC_FOUND_ROWS A.id,A.alias,A.name,DATE_FORMAT(created,'%%d.%%m.%%Y') created,DATE_FORMAT(updated,'%%d.%%m.%%Y')updated
            ,DATE_FORMAT(published,'%%d.%%m.%%Y')published,A.active,A.cost,COALESCE(B.qty,0) qty,A.default_image FROM filterpreset A
            LEFT JOIN filterpreset__qty B ON(A.id=B.id)
            %s %s %s %s;";
    }

    protected function get_filters() {
        return [
            'id' => 'Int:A.id',
            'alias' => 'String:A.alias',
            'name' => 'String:A.name',
            'created' => 'Date:A.created',
            'updated' => 'Date:A.updated',
            'published' => 'Date:A.published',
            'active' => 'Int:A.active',
            'cost' => 'Numeric:A.cost',
            'qty' => 'Int:COALESCE(B.qty,0)',
        ];
    }

    protected function get_sorts() {
        return [
            'id' => 'A.id',
            'alias' => 'A.alias|A.id',
            'name' => 'A.name|A.id',
            'created' => 'A.created|A.id',
            'updated' => 'A.updated|A.id',
            'published' => 'A.published|A.id',
            'active' => 'A.active|A.id',
            'cost' => 'A.cost|A.id',
            'qty' => 'COALESCE(B.qty,0)|A.id',
        ];
    }

    /**
     * 
     * @param \Out\IOut $out
     * @return array
     */
    public function run(\Out\IOut $out = null): array {

        $query_proto = $this->get_query_proto();
        $c = 0;
        $p = [];
        $this->pre_request($p, $c);
        $where = $this->filter->buildSQL($p, $c);
        $query = sprintf($query_proto, $this->filter->whereWord, $where, $this->order->SQL, $this->limit->MySqlLimit);
        $rows = \DB\DB::F()->queryAll($query, $p);
        if (!count($rows) && $this->limit->page) {
            $this->limit->setPage(0);
            $query = sprintf($query_proto, $this->filter->whereWord, $where, $this->order->SQL, $this->limit->MySqlLimit);
            $rows = \DB\DB::F()->queryAll($query, $p);
        }
        $total = \DB\DB::F()->get_found_rows();

        $result = [
            'total' => $total, 'items' => $rows, 'perpage' => $this->limit->perpage, 'page' => $this->limit->page,
        ];
        if ($out) {
            foreach ($result as $k => $v) {
                $out->add($k, $v);
            }
        }
        $this->after_process($rows, $result, $this->input, $out);
        return $result;
    }

    protected function pre_request(array &$params, int &$c) {
        
    }

    protected function after_process(array $items, array &$result, \DataMap\IDataMap $input, \Out\Out $out = null) {
        
    }

    /**
     * 
     * @param \DataMap\IDataMap $input
     * @return \Content\FilterPreset\Lister
     */
    public static function F(\DataMap\IDataMap $input = null): Lister {
        return new static($input);
    }

}
