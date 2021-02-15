<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia;

/**
 * Description of MyPublicGalleryLister
 *
 * @author eve
 */
class UserPublicGalleryLister {

    /** @var \DataMap\IDataMap */
    protected $input;

    /** @var \DataMap\ADVTIDataBridge */
    protected $bridge;

    /** @var \Out\IOut */
    protected $output;

    /** @var \ADVTable\Filter\FixedTokenFilter */
    protected $filter;

    /** @var \ADVTable\Limit\FixedTokenLimit */
    protected $limit;

    /** @var \ADVTable\Sort\FixedTokenSort */
    protected $order;
    protected $user_id;

    public function __construct(\DataMap\IDataMap $input, int $user_id, \Out\IOut $out = null) {
        $this->input = $input;
        $this->output = $out;
        $this->bridge = \DataMap\ADVTIDataBridge::F($this->input);
        $this->filter = \ADVTable\Filter\FixedTokenFilter::F($this->bridge, $this->get_filters());
        $this->order = \ADVTable\Sort\FixedTokenSort::F($this->bridge, $this->get_sorts());
        $this->limit = \ADVTable\Limit\FixedTokenLimit::F($this->bridge);
        $this->user_id = $user_id;
        $this->filter->addDirectCondition("(A.owner_id=:P_fixed_user)");
        $this->order->tokens_separator = "|";
    }

    protected function get_filters() {
        return [];
    }

    protected function get_sorts() {
        return [];
    }

    public function run(\Out\IOut $out = null) {
        $query_proto = "SELECT A.id,A.owner_id,A.name,A.visible,A.cover_aspect,  COALESCE(C.qty,0) qty,  DATE_FORMAT(U.updated,'%%d.%%m.%%Y') updated FROM public__gallery A 
            LEFT JOIN public__gallery__up U ON(A.id=U.id) 
            LEFT JOIN public__gallery__counter C ON(C.id=A.id) %s %s %s %s;";
        $p = [":P_fixed_user" => $this->user_id];
        $c = 0;
        $where = $this->filter->buildSQL($p, $c);
        $query = sprintf($query_proto, $this->filter->whereWord, $where, $this->order->SQL, $this->limit->MySqlLimit);
        $rows = \DB\DB::F()->queryAll($query, $p);
        if (!count($rows) && $this->limit->page) {
            $this->limit->setPage(0);
            $query = sprintf($query_proto, $this->filter->whereWord, $where, $this->order->SQL, $this->limit->MySqlLimit);
            $rows = \DB\DB::F()->queryAll($query, $p);
        }
        $total = \DB\DB::F()->get_found_rows();
        $outs = [];
        if ($out && $out !== $this->output) {
            $outs[] = $out;
        }
        $this->output ? $outs[] = $this->output : 0;
        foreach ($outs as $xout /* @var $xout \Out\IOut */) {
            $xout->add("items", $rows)->add("total", $total)->add("page", $this->limit->page)->add('perpage', $this->limit->perpage);
        }
        return [
            'items' => $rows,
            'total' => $total,
            'page' => $this->limit->page,
            'perpage' => $this->limit->perpage,
        ];
    }

    public static function F(\DataMap\IDataMap $input, int $user_id, \Out\IOut $out = null): UserPublicGalleryLister {
        return new static($input, $user_id, $out);
    }

}
