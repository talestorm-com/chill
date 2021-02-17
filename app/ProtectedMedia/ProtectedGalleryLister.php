<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ProtectedMedia;

/**
 * Description of ProtectedGalleryLister
 *
 * @author eve
 */
class ProtectedGalleryLister {
    //put your code here

    /** @var \ADVTable\Data\IData */
    private $input;

    /** @var \ADVTable\Filter\FixedTokenFilter */
    private $filter;

    /** @var \ADVTable\Limit\FixedTokenLimit */
    private $limit;

    /** @var \ADVTable\Sort\FixedTokenSort */
    private $order;
    private $user_id = null;

    protected function get_filters() {
        return [
            'title' => 'String:A.title',
            'created' => 'Date:C.created',
            'updated' => 'Date:C.updated',
            "qty"=>"Int:COALESCE(B.qty,0)"
        ];
    }

    protected function get_sorts() {
        return [
            'title' => 'A.title|A.uid',
            'sort' => 'A.sort|A.uid',
            'created' => 'C.created|A.uid',
            'updated' => 'C.updated|A.uid',
            'qty' => 'COALESCE(B.qty,0)|A.uid',
        ];
    }

    public function __construct(\DataMap\IDataMap $input, int $user_id = null) {
        $this->user_id = $user_id;
        $this->input = \DataMap\ADVTIDataBridge::F($input);
        $this->filter = \ADVTable\Filter\FixedTokenFilter::F($this->input, $this->get_filters());
        $this->limit = \ADVTable\Limit\FixedTokenLimit::F($this->input);
        $this->order = \ADVTable\Sort\FixedTokenSort::F($this->input, $this->get_sorts());
        $this->order->tokens_separator="|";
    }

    public static function F(\DataMap\IDataMap $input = null, $user_id = null) {
        return new static($input ? $input : \DataMap\InputDataMap::F(), $user_id);
    }

    public function get(\Out\IOut $out = null) {
        $params = [];
        $counter = 0;
        if ($this->user_id) {
            $this->filter->addDirectCondition(" (A.owner_id=:Puser) ");
            $params[":Puser"] = $this->user_id;
        }
        $p_query = "SELECT SQL_CALC_FOUND_ROWS A.uid,A.owner_id,A.title,A.sort,COALESCE(B.qty,0)qty,C.created,C.updated FROM protected__gallery A 
            LEFT JOIN protected__gallery__counter B ON(A.uid=B.uid AND A.owner_id=B.owner_id)
            LEFT JOIN protected__gallery__dates C ON(A.uid=C.uid AND A.owner_id=C.owner_id)
            %s %s %s %s
            ";
        $where = $this->filter->buildSQL($params, $counter);
        $query = sprintf($p_query, $this->filter->whereWord, $where, $this->order->SQL, $this->limit->MySqlLimit);
        $items = \DB\DB::F()->queryAll($query, $params);
        if (!count($items) && $this->limit->page) {
            $this->limit->page = 0;
            $query = sprintf($p_query, $this->filter->whereWord, $where, $this->order->SQL, $this->limit->MySqlLimit);
            $items = \DB\DB::F()->queryAll($query, $params);
        }
        $total = \DB\DB::F()->get_found_rows();
        $page = $this->limit->page;
        $perpage = $this->limit->perpage;

        $result = compact('items', 'total', 'page', 'perpage');
        if ($out) {
            $out->add("items", $items)->add("total", $total)->add("page", $page)->add("perpage", $perpage);
        }
        return $result;
    }

}
