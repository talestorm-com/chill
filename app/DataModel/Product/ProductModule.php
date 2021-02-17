<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product;

class ProductModule implements \ModulesSupport\Products\IModuleProducts {

    /** @var ProductModule */
    protected static $instance;

    protected function __construct() {
        static::$instance = $this;
    }

    public function advt_list_products(\Out\IOut $out, \ADVTable\Data\IData $input = null) {
        ADVTLister::F($out, $input)->run();
    }

    public function editor_post_product(\Out\IOut $out, \DataMap\IDataMap $input = null) {
        $ret_id = Writer\ProductWriter::F($out, $input)->run();
        $this->editor_get_product($out, $input, $ret_id);
    }

    /**
     * 
     * @return \DataModel\Product\ProductModule
     */
    public static function F(): ProductModule {
        return static::$instance ? static::$instance : new static();
    }

    public function advt_get_table_columns(): array {
        return include __DIR__ . DIRECTORY_SEPARATOR . "ADVTTableColumns.php";
    }

    public function editor_get_product(\Out\IOut $out, \DataMap\IDataMap $input = null, int $ret_id = null) {
        $input = $input ? $input : \DataMap\GPDataMap::F();
        $product_id = $ret_id ? $ret_id : $input->get_filtered('product_id', ['IntMore0', 'DefaultNull']);
        $product_id ? FALSE : \Errors\common_error::R("invalid request");
        $product = Model\ProductModel::F($product_id);
        $product ? false : \Errors\common_error::R("not found");
        $out->add('product', $product->marshall());
    }

    public function list_products_of_catgeory(\Out\IOut $out, \DataMap\IDataMap $input = null) {
        idLister::F($out, $input)->run();
    }

    protected function get_products_ids(\DataMap\IDataMap $input, string $key = 'products'): array {
        $param_pool = \Filters\params\ArrayParamBuilder::B(['ArrayOfInt' => ['count_min' => 1, 'min' => 1]], true);
        $result = $input->get_filtered($key, ['NEArray', 'ArrayOfInt', 'DefaultNull'], $param_pool->get_param_set_for_property());
        $result ? 0 : \Errors\common_error::R("invalid input list");
        return $result;
    }

    public function link_products(\Out\IOut $out, \DataMap\IDataMap $input = null) {
        $input = $input ? $input : \DataMap\GPDataMap::F();
        $ids = $this->get_products_ids($input);
        $target = $input->get("target", ['IntMore0', 'DefaultNull']);
        $target ? 0 : \Errors\common_error::R("no target");
        $builder = \DB\SQLTools\SQLBuilder::F();
        $inserts = [];
        $c = 0;
        $p = [];
        foreach ($ids as $id) {
            $inserts[] = "(:P{$c}product,:Pgroup)";
            $p[":P{$c}product"] = $id;
            $c++;
        }
        if (count($inserts)) {
            $p[":Pgroup"] = $target;
            $builder->push(sprintf("INSERT INTO catalog__product__group(product_id,group_id) VALUES %s ON DUPLICATE KEY UPDATE product_id=VALUES(product_id);", implode(",", $inserts)))
                    ->push_params($p)->execute_transact();
            \DataModel\Product\Model\ProductModel::RESET_CACHE();
        }
    }

    public function move_products(\Out\IOut $out, \DataMap\IDataMap $input = null) {
        $input = $input ? $input : \DataMap\GPDataMap::F();
        $ids = $this->get_products_ids($input);
        $target = $input->get("target", ['IntMore0', 'DefaultNull']);
        $target ? 0 : \Errors\common_error::R("no target");
        $from = $input->get("from", ['IntMore0', 'DefaultNull']);
        $from ? 0 : \Errors\common_error::R("no target");
        $tn = "a" . md5(__METHOD__);
        $q = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
            CREATE TEMPORARY TABLE (product INT(11) UNSIGNED NOT NULL,PRIMARY KEY(product))ENGINE=MEMORY;
            INSERT INTO `{$tn}` (product) VALUES %s ON DUPLICATE KEY UPDATE product=VALUES(product);
        ";
        $inserts = [];
        $c = 0;
        $p = [];
        foreach ($ids as $id) {
            $inserts[] = "(:P{$c}product)";
            $p[":P{$c}product"] = $id;
            $c++;
        }
        if (count($inserts)) {
            \DB\DB::F()->exec(sprintf($q, implode(",", $inserts)), $p);
            $b = \DB\SQLTools\SQLBuilder::F();
            $b->push("
                DELETE A.* FROM catalog__product__group A JOIN `{$tn}` B ON(A.product_id=B.product)
                    WHERE A.group_id=:P1;
                INSERT INTO catalog__product__group (group_id,product_id)    
                   SELECT :P2,product FROM `{$tn}`
                ON DUPLICATE KEY UPDATE product_id=VALUES(product_id);
                ")->push_params([":P1" => $from, ":P2" => $target])->execute_transact();
            \DataModel\Product\Model\ProductModel::RESET_CACHE();
        }
    }

    public function remove_products(\Out\IOut $out, \DataMap\IDataMap $input = null) {
        $input = $input ? $input : \DataMap\GPDataMap::F();
        $ids = $this->get_products_ids($input);
        $tn = "a" . md5(__METHOD__);
        $q = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
            CREATE TEMPORARY TABLE `{$tn}`(product INT(11) UNSIGNED NOT NULL,PRIMARY KEY(product))ENGINE=MEMORY;
            INSERT INTO `{$tn}` (product) VALUES %s ON DUPLICATE KEY UPDATE product=VALUES(product);
        ";
        $inserts = [];
        $c = 0;
        $p = [];
        foreach ($ids as $id) {
            $inserts[] = "(:P{$c}product)";
            $p[":P{$c}product"] = $id;
            $c++;
        }
        if (count($inserts)) {
            \DB\DB::F()->exec(sprintf($q, implode(",", $inserts)), $p);
            \DB\errors\MySQLWarn::F(\DB\DB::F());
            $b = \DB\SQLTools\SQLBuilder::F();
            $b->push("DELETE A.* FROM catalog__product A JOIN `{$tn}` B ON(A.id=B.product);");
            $b->execute_transact();
            $rows_to_delete = \DB\DB::F()->queryAll("SELECT product id FROM `{$tn}`;");
            foreach ($rows_to_delete as $row) {
                $id = \Filters\FilterManager::F()->apply_chain($row['id'], ['IntMore0', 'DefaultNull']);
                if ($id) {
                    \ImageFly\ImageFly::F()->remove_images(\ImageFly\IMediaContext::PRODUCT, $id);
                }
            }
            ColorCleanout\ColorCleanout::mk_params()->run();
            \DataModel\Product\Model\ProductModel::RESET_CACHE();
        }
    }

    public function unlink_products(\Out\IOut $out, \DataMap\IDataMap $input = null) {
        $input = $input ? $input : \DataMap\GPDataMap::F();
        $ids = $this->get_products_ids($input);
        $from = $input->get("from", ['IntMore0', 'DefaultNull']);
        $tn = "a" . md5(__METHOD__);
        $q = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
            CREATE TEMPORARY TABLE `{$tn}`(product INT(11) UNSIGNED NOT NULL,PRIMARY KEY(product))ENGINE=MEMORY;
            INSERT INTO `{$tn}` (product) VALUES %s ON DUPLICATE KEY UPDATE product=VALUES(product);
        ";
        $inserts = [];
        $c = 0;
        $p = [];
        foreach ($ids as $id) {
            $inserts[] = "(:P{$c}product)";
            $p[":P{$c}product"] = $id;
            $c++;
        }
        if (count($inserts)) {
            \DB\DB::F()->exec(sprintf($q, implode(",", $inserts)), $p);
            \DB\errors\MySQLWarn::F(\DB\DB::F());
            $b = \DB\SQLTools\SQLBuilder::F();
            if ($from) {
                $b->push("
                DELETE A.* FROM catalog__product__group A JOIN `{$tn}` B ON(A.product_id=B.product)
                    WHERE A.group_id=:P1;                
                ")->push_params([":P1" => $from]);
            } else {
                $b->push("DELETE A.* FROM catalog__product__group A JOIN `{$tn}` B ON(A.product_id=B.product);");
            }
            $b->execute_transact();
            \DataModel\Product\Model\ProductModel::RESET_CACHE();
        }
    }

    public function apply_sort(\Out\IOut $out, array $sorts) {
        $inserts = [];
        $params = [];
        $c = 0;
        foreach ($sorts as $sort_row) {
            if (is_array($sort_row)) {
                $id = \Filters\FilterManager::F()->apply_chain($sort_row['i'], ['IntMore0', 'DefaultNull']);
                $val = \Filters\FilterManager::F()->apply_chain($sort_row['v'], ['Int', 'DefaultNull']);
                if (null !== $id && null !== $val) {
                    $inserts[] = "(:P{$c}i,:P{$c}v)";
                    $params[":P{$c}i"] = $id;
                    $params[":P{$c}v"] = $val;
                    $c++;
                }
            }
        }        
        if (count($inserts)) {         
            $tn = "a" . md5(__METHOD__);
            $query = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
                DROP TEMPORARY TABLE IF EXISTS `{$tn}r`;
                CREATE TEMPORARY TABLE `{$tn}` (id INT(11) UNSIGNED NOT NULL,sort INT(11) NOT NULL DEFAULT 0,PRIMARY KEY(id));
                CREATE TEMPORARY TABLE `{$tn}r` (id INT(11) UNSIGNED NOT NULL,sort INT(11) NOT NULL DEFAULT 0,PRIMARY KEY(id));    
                INSERT INTO `{$tn}` (id,sort) VALUES " . implode(",", $inserts) . " ON DUPLICATE KEY UPDATE sort=VALUES(sort);";
            \DB\DB::F()->exec($query, $params);
            \DB\errors\MySQLWarn::F(\DB\DB::F());
            $ranking_query = "
                UPDATE catalog__product A JOIN `{$tn}` B ON(A.id=B.id)
                    SET A.sort=B.sort;
                SET @ranking = 0;    
                INSERT INTO `{$tn}r` (id,sort)
                    SELECT A.id, @ranking := (@ranking+10)
                    FROM catalog__product A 
                    ORDER BY A.sort,A.id DESC;
                UPDATE catalog__product A JOIN `{$tn}r` B ON(A.id=B.id)
                    SET A.sort=B.sort;  
                ";
            \DB\DB::F()->exec($ranking_query);
            \DB\errors\MySQLWarn::F(\DB\DB::F());
            \DataModel\Product\Model\ProductModel::RESET_CACHE();
        }
    }

}
