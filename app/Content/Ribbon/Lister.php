<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Ribbon;

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
        return "SELECT SQL_CALC_FOUND_ROWS 
            A.id,A.active,A.title,DATE_FORMAT(A.published,'%%d.%%m.%%Y')published,
            COALESCE(A.image,PST.default_image,VTR.default_image,null) image,
            CASE WHEN A.image IS NOT NULL THEN '".RibbonItem::MEDIA_CONTEXT."'
                 WHEN PST.default_image IS NOT NULL THEN '". \Content\FilterPreset\FilterPreset::MEDIA_CONTEXT ."'
                 WHEN VTR.default_image IS NOT NULL THEN '".\Content\Video\VideoGroup::MEDIA_CONTEXT."'
                 ELSE NULL END image_context,
            CASE WHEN A.image IS NULL THEN A.link_id ELSE A.id END image_owner_id,
            A.link_type,A.link_id,A.link_uid,A.target
            FROM ribbon A 
            LEFT JOIN filterpreset PST ON (A.link_type='".\Content\FilterPreset\FilterPreset::ACCESS_KEY."' AND A.link_id=PST.id)
            LEFT JOIN video__group VTR ON (A.link_type='". \Content\Video\VideoGroup::ACCESS_KEY."' AND A.link_id=VTR.id)
                
            %s %s %s %s;";
    }

    protected function get_filters() {
        return [
            'id' => 'Int:A.id',
            'active' => 'Int:A.active',
            'title' => 'String:A.title',            
            'published' => 'Date:A.published',            
        ];
    }

    protected function get_sorts() {
        return [
            'id' => 'A.id',
            'title' => 'A.title|A.id',                        
            'published' => 'A.published|A.id',
            'active' => 'A.active|A.id',            
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
        $this->filter->addDirectCondition(' (A.target=:P_default_target) ');
        $params[":P_default_target"]='*';
    }

    protected function after_process(array $items, array &$result, \DataMap\IDataMap $input, \Out\Out $out = null) {
        
    }

    /**
     * 
     * @param \DataMap\IDataMap $input
     * @return \Content\Ribbon\Lister
     */
    public static function F(\DataMap\IDataMap $input = null): Lister {
        return new static($input);
    }

}
