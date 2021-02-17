<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Lister;

/**
 * Description of Lister
 *
 * @author eve
 * @property \ADVTable\Data\IData $bridge
 * @property \DataMap\IDataMap $input
 * @property \ADVTable\Filter\AbstractFilter $filter
 * @property \ADVTable\Limit\AbstractLimit $limit
 * @property \ADVTable\Sort\AbstractSort $sort
 * @property array $params
 * @property integer $counter
 * @property string $where  compiled where condition
 */
class Lister {

    use \common_accessors\TCommonAccess;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var \ADVTable\Data\IData */
    protected $bridge;

    /** @var \DataMap\IDataMap */
    protected $input;

    /** @var \ADVTable\Filter\AbstractFilter */
    protected $filter;

    /** @var \ADVTable\Limit\AbstractLimit */
    protected $limit;

    /** @var \ADVTable\Sort\AbstractSort */
    protected $sort;

    /** @var array */
    protected $params;

    /** @var integer */
    protected $counter;

    /** @var string */
    protected $where;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return \ADVTable\Data\IData */
    protected function __get__bridge() {
        return $this->bridge;
    }

    /** @return \DataMap\IDataMap */
    protected function __get__input() {
        return $this->input;
    }

    /** @return \ADVTable\Filter\AbstractFilter */
    protected function __get__filter() {
        return $this->filter;
    }

    /** @return \ADVTable\Limit\AbstractLimit */
    protected function __get__limit() {
        return $this->limit;
    }

    /** @return \ADVTable\Sort\AbstractSort */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return string */
    protected function __get__where() {
        return $this->where;
    }

    //</editor-fold>

    /**
     * 
     * @param \DataMap\IDataMap $in
     */
    public function __construct(\DataMap\IDataMap $in) {
        $this->input = $in;
        $this->init_bridge()
                ->init_filter()
                ->init_limit()
                ->init_sort()
                ->init_parametrizer();
    }

    /**
     * 
     * @return $this
     */
    protected function init_parametrizer() {
        $this->params = [];
        $this->counter = 0;
        return $this;
    }

    /**
     * 
     * @return $this
     */
    protected function init_filter() {
        $this->filter = \ADVTable\Filter\FixedTokenFilter::F($this->bridge, $this->get_filters());
        return $this;
    }

    /**
     * 
     * @return $this
     */
    protected function init_limit() {
        $this->limit = \ADVTable\Limit\FixedTokenLimit::F($this->bridge);
        return $this;
    }

    /**
     * 
     * @return string
     */
    protected function get_sort_separator(): string {
        return "|";
    }

    /**
     * 
     * @return $this
     */
    protected function init_sort() {
        $this->sort = \ADVTable\Sort\FixedTokenSort::F($this->bridge, $this->get_sorts());
        $this->sort->tokens_separator = $this->get_sort_separator();
        return $this;
    }

    /**
     * 
     * @return $this
     */
    protected function init_bridge() {
        $this->bridge = \DataMap\ADVTIDataBridge::F($this->input);
        return $this;
    }

    /**
     * 
     * @return array
     */
    protected function get_filters(): array {
        return [];
    }

    /**
     * 
     * @return array
     */
    protected function get_sorts(): array {
        return [];
    }

    /**
     * 
     * @param \DataMap\IDataMap $in
     * @return \Content\Lister\Lister
     */
    public static function F(\DataMap\IDataMap $in): Lister {
        return new static($in);
    }

    /**
     * 
     * @return \DB\IDBAdapter
     */
    protected function get_adapter(): \DB\IDBAdapter {
        return \DB\DB::F();
    }

    /**
     * 
     * @param \Out\IOut $out
     * @return \Content\Lister\ListerResult
     */
    public function run(\Out\IOut $out = null): ListerResult {
        $this->create_direct_conditions();
        $this->where = $this->filter->buildSQL($this->params, $this->counter);
        $db = $this->get_adapter();
        $this->execute_pre_query($db);
        $query = $this->build_query();
        $items = $db->queryAll($query, $this->params);
        if (!count($items) && $this->limit->page) {
            $this->limit->page = 0;
            $query = $this->build_query();
            $items = $db->queryAll($query, $this->params);
        }
        $total = $db->queryScalari("SELECT FOUND_ROWS();");
        $result = ListerResult::F($items, $total, $this->limit);
        $out ? $result->to_out($out) : 0;
        return $result;
    }
    
    
    
    
    protected function create_direct_conditions(){
        
    }


    protected function execute_pre_query(\DB\IDBAdapter $adapter){
        
    }
    
    protected function build_query(){
        
    }

}
