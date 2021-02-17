<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Lister;

/**
 * Description of ListerResult
 *
 * @author eve
 * @property array $items
 * @property int $total
 * @property int $page
 * @property int $perpage
 */
class ListerResult implements \common_accessors\IMarshall {

    use \ADVTable\Util\TAccess,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var array */
    protected $items;

    /** @var int */
    protected $total;

    /** @var int */
    protected $page;

    /** @var int */
    protected $perpage;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return array */
    protected function __get__items() {
        return $this->items;
    }

    /** @return int */
    protected function __get__total() {
        return $this->total;
    }

    /** @return int */
    protected function __get__page() {
        return $this->page;
    }

    /** @return int */
    protected function __get__perpage() {
        return $this->perpage;
    }

    //</editor-fold>


    public function __construct(array $items, int $total, \ADVTable\Limit\AbstractLimit $limit) {
        $this->items = $items;
        $this->total = $total;
        $this->page = $limit->page;
        $this->perpage = $limit->perpage;
    }

    /**
     * 
     * @param array $items
     * @param int $total
     * @param \ADVTable\Limit\AbstractLimit $limit
     * @return \Content\Lister\ListerResult
     */
    public static function F(array $items, int $total, \ADVTable\Limit\AbstractLimit $limit): ListerResult {
        return new static($items, $total, $limit);
    }

    /**
     * writes result to outpiut (directly, no marshalling)
     * @param \Out\IOut $out
     * @return \Content\Lister\ListerResult
     */
    public function to_out(\Out\IOut $out): ListerResult {
        $out->add('items', $this->items)
                ->add('total', $this->total)
                ->add('page', $this->page)
                ->add('perpage', $this->perpage);
        return $this;
    }

}
