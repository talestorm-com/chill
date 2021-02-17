<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia;

/**
 * Description of UserPublicItemsLister
 *
 * @author eve
 */
class UserPublicItemsLister {

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
        $this->filter->addDirectCondition("(B.owner_id=:P_fixed_user)");
        $this->order->tokens_separator = "|";
    }

    public function run(\Out\IOut $out = null) {
        $query_proto = "SELECT 
            A.id,B.id gallery_id,A.title,B.name gallery_name,B.owner_id,A.active,A.type,A.sort,A.extension,A.aspect,A.preview_aspect,A.created
            FROM public__gallery__item A
            JOIN public__gallery B ON(A.gallery_id=B.id)
            %s %s %s %s;";
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

    public static function F(\DataMap\IDataMap $input, int $user_id, \Out\IOut $out = null): UserPublicItemsLister {
        return new static($input, $user_id, $out);
    }

    protected function get_filters() {
        return [
            'id' => 'Int:A.uid',
            'gallery_id' => 'Int:A.gallery_id',
            'title' => 'String:A.title',
            'gallery_name' => 'String:B.name',
            'active' => 'Int:A.active',
            'type' => 'String:A.type',
            'created' => 'Date:A.created',
        ];
    }

    protected function get_sorts() {
        return [
            'title' => 'A.title|A.sort|A.id',
            'gallery_name' => 'B.name|A.sort|A.id',
            'created' => 'A.created|A.sort|A.id',
            'natural' => 'A.sort|A.created DESC|A.id',
        ];
    }

}
