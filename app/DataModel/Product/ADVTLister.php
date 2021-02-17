<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product;

class ADVTLister {

    protected $out;
    protected $input;

    protected function __construct(\Out\IOut $o, \ADVTable\Data\IData $i = null) {
        $this->out = $o;
        $this->input = $i;
    }

    protected function create_catalog_support_table(int $group): string {
        $tn = "a" . md5(__METHOD__);
        $list = \CatalogTree\CatalogTreeSinglet::F()->tree->enum_childs_ids_of_id($group, true);
        $query = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
            CREATE TEMPORARY TABLE `{$tn}` (id INT(11) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MEMORY;
            ";
        if (count($list)) {
            $query .= "INSERT INTO `{$tn}`(id) VALUES (" . implode("),(", array_unique($list)) . ") ON DUPLICATE KEY UPDATE id=VALUES(id);";
        }
        \DB\DB::F()->exec($query);
        return $tn;
    }

    protected function get_advt_filters() {
        return [
            'id' => 'Int:A.id',
            'article' => 'String:A.article',
            'alias' => 'String:A.alias',
            'name'=>'String:S.name',
            'sort'=>'Int:A.sort',
            'price_gross'=>'Numeric:CPP.gross',
            'price_retail'=>'Numeric:CPP.retail',
            'actioned'=>'Int:CASE WHEN (COALESCE(CPP.discount_retail,0)+COALESCE(CPP.discount_gross,0)+COALESCE(CPP.retail_old,0)+COALESCE(CPP.gross_old,0))>0 THEN 1 ELSE 0 END',
        ];
    }

    protected function get_advt_sorts() {
        return ['id' => 'A.id',
            'article' => 'B.article|A.id',
            'alias' => 'B.alias|A.id',
            'name' => 'S.name|A.id',
            'sort' => 'B.sort|A.id DESC',
            'price_gross'=>'CPP.gross|B.sort|A.id DESC',
            'price_retail'=>'CPP.retail|B.sort|A.id DESC',
            'actioned'=>'CASE WHEN (COALESCE(CPP.discount_retail,0)+COALESCE(CPP.discount_gross,0)+COALESCE(CPP.retail_old,0)+COALESCE(CPP.gross_old,0))>0 THEN 1 ELSE 0 END|B.sort|A.id DESC',
        ];        
    }

    /**
     * run a query
     */
    public function run() {
        $condition = \ADVTable\Filter\FixedTokenFilter::F($this->input, $this->get_advt_filters());
        $direction = \ADVTable\Sort\FixedTokenSort::F($this->input, $this->get_advt_sorts());
        $direction->tokens_separator = '|';
        $limitation = \ADVTable\Limit\FixedTokenLimit::F($this->input);
        $group = \Filters\FilterManager::F()->apply_chain($condition->filter_value("catalog", null), ['Int', 'DefaultNull']);
        null === $group || ($group < 1 && ($group !== -606 && $group != -1)) ? $group = -606 : false;
        $join = "";
        if ($group > 0) {//linked
            $tn = $this->create_catalog_support_table($group);
            $join = " JOIN catalog__product__group CPJ ON(CPJ.product_id=A.id) JOIN `{$tn}` CPJF ON(CPJF.id=CPJ.group_id)";
        } else if ($group === -1) {//not linked
            $join = " LEFT JOIN catalog__product__group CPJ ON(CPJ.product_id=A.id)";
            $condition->addDirectCondition("(CPJ.group_id IS NULL)");
        } else if ($group === -606) {//all
            // none additional conditions required
        }
        /// нужно избежать дубликатов из разных категорий - поэтому сначала запускаем запрос по id и без лимита,
        // а потом по нему выбираем товары
        $tna = "p" . md5(__METHOD__);
        \DB\DB::F()->exec("DROP TEMPORARY TABLE IF EXISTS `{$tna}`; CREATE TEMPORARY TABLE `{$tna}` (id INT(11) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MEMORY;");
        $params = [];
        $c = 0;
        $where = $condition->buildSQL($params, $c);
        $q2 = " INSERT INTO `{$tna}` SELECT A.id FROM
            catalog__product A JOIN catalog__product__strings S ON(S.id=A.id)
            LEFT JOIN catalog__product__price CPP ON(CPP.id=A.id)
            %s %s %s ON DUPLICATE KEY UPDATE id=VALUES(id);
            ";
        \DB\DB::F()->exec(sprintf($q2, $join, $condition->whereWord, $where), $params);
        $qf = "SELECT SQL_CALC_FOUND_ROWS A.id,guid,alias,article,enabled,name,B.sort,CPP.retail,CPP.gross,
            CASE WHEN (COALESCE(CPP.discount_retail,0)+COALESCE(CPP.discount_gross,0)+COALESCE(CPP.retail_old,0)+COALESCE(CPP.gross_old,0))>0 THEN 1 ELSE 0 END actioned
            FROM `{$tna}` A JOIN catalog__product B ON(A.id=B.id) JOIN catalog__product__strings S ON(S.id=A.id)
                LEFT JOIN catalog__product__price CPP ON(CPP.id=A.id)
                %s %s";
         $this->out->add("debug_query", sprintf($qf, $direction->SQL, $limitation->MySqlLimit));   
        $items = \DB\DB::F()->queryAll(sprintf($qf, $direction->SQL, $limitation->MySqlLimit));
        $total = \DB\DB::F()->get_found_rows();
        if ($total && !count($items) && $limitation->page > 0) {
            $limitation->setPage(0);
            $items = \DB\DB::F()->queryAll(sprintf($qf, $direction->SQL, $limitation->MySqlLimit));
            $total = \DB\DB::F()->get_found_rows();
        }
        $this->out->add('items', $items)->add('total', $total)->add('page', $limitation->page)->add('perpage', $limitation->perpage);
    }

    /**
     * 
     * @param \Out\IOut $out
     * @param \ADVTable\Data\IData $input
     * @return \ModulesSupport\Product\ADVTLister
     */
    public static function F(\Out\IOut $out, \ADVTable\Data\IData $input = null): ADVTLister {
        return new static($out, $input);
    }

}
