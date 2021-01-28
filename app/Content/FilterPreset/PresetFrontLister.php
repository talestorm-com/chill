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
class PresetFrontLister {

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
        return "SELECT SQL_CALC_FOUND_ROWS A.id package_id,A.uid,A.name,B.name package_name,A.image,A.sort,DATE_FORMAT(B.published,'%%d.%%m.%%Y') published,
            B.cost package_cost
            FROM filterpreset__item A JOIN filterpreset B ON(A.id=B.id)
            %s %s %s %s;
            ";
    }

    protected function get_filters() {
        return [
            'package_id' => 'Int:B.id',
            'uid' => 'String:A.uid',
            'name' => 'String:A.name',
            'package_name' => 'String:B.name',
            'published' => 'Date:B.published',
        ];
    }

    protected function get_sorts() {
        return [
            'package_id' => 'B.id|A.sort|A.uid',
            'name' => 'A.name|B.id|A.sort|A.uid',
            'published' => 'B.published|B.id|A.sort|A.uid',
            'package_name' => 'B.name|B.id|A.sort|A.uid',
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
        $this->post_result($rows,$result,$this->input,$out);
        return $result;
    }
    
    
    protected function post_result(array $rows,array &$result, \DataMap\IDataMap $input, \Out\IOut $out = null){
        if ($input->get_filtered("with_acl", ["Boolean", "DefaultFalse"])) {
            $access_list = [];
            $am = \Auth\ProductAccessMonitor::F();
            foreach ($rows as $row) {
                $cost = floatval($row["package_cost"]);
                $key = implode("", [FilterPreset::ACCESS_KEY, $row['package_id']]);
                if ($cost > 0) {
                    $access_list[$key] = $am->has_access_to_preset((string) $row['package_id']);
                } else {
                    $access_list[$key] = true;
                }
            }
            $result["acl"] = $access_list;
            $out ? $out->add("acl", $access_list) : 0;
        }
    }

    protected function pre_request(array &$params, int &$c) {
        $this->filter->addDirectCondition("(B.active=1)");
    }

    /**    
     * @param \DataMap\IDataMap $input
     * @return \Content\FilterPreset\PresetFrontLister
     */
    public static function F(\DataMap\IDataMap $input = null): PresetFrontLister {
        return new static($input);
    }

}
