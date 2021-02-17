<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product;

class idLister {

    protected $out;
    protected $input;

    protected function __construct(\Out\IOut $o, \DataMap\IDataMap $i = null) {
        $this->out = $o;
        $this->input = $i;
        $this->input ? 0 : $this->input = \DataMap\GPDataMap::F();
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

    /**
     * run a query
     */
    public function run() {
        $group = $this->input->get_filtered('of', ['IntMore0', 'DefaultNull']);
        $result = [];
        if ($group) {
            $tn = $this->create_catalog_support_table($group);
            $query = "SELECT P.id FROM 
                `{$tn}` A JOIN
                    catalog__product__group CPG ON(CPG.group_id=A.id)
                JOIN catalog__product P ON(P.id=CPG.product_id)                                
                ";
            $rows = \DB\DB::F()->queryAll($query);
            foreach ($rows as $row) {
                $x = intval($row['id']);
                $x ? $result[] = $x : 0;
            }
            $result = array_unique($result);
        }
        $this->out->add('ids', array_values($result));
    }

    /**
     * 
     * @param \Out\IOut $out
     * @param \DataMap\IDataMap $input
     * @return \ModulesSupport\Product\idLister
     */
    public static function F(\Out\IOut $out, \DataMap\IDataMap $input = null): idLister {
        return new static($out, $input);
    }

}
