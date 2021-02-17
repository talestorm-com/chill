<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ns23b41a9e233048dc802a9cc2982f792b;

require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "www" . DIRECTORY_SEPARATOR . "__bootstrap.php";

/**
 * Description of url_fix
 *
 * @author eve
 */
class url_fix {

    protected $url_arts;
    protected $redirects = [];

    /** @var \CatalogTree\CatalogTree */
    protected $catalog_tree;

    protected function __construct() {
        $this->url_arts = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "result.json"), true);
        $this->redirects = [];
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    public function run() {
        foreach ($this->url_arts as $url => $article) {
            $alias = $this->get_product_alias_by_article($article);
            if ($alias) {
                $this->redirects[$url] = "/product/{$alias}";
            }
        }
        if (count($this->redirects)) {
            $this->export_redirects();
        }
        die(sprintf("finished. total %s redirects found,%s is targets", count($this->redirects), count(array_unique(array_values($this->redirects)))));
    }

    protected function export_redirects() {
        foreach ($this->redirects as $old_url => $new_url) {
            echo "{$old_url}=>{$new_url}\n";
        }
        $inserts = [];
        $q = "DELETE  FROM redirects; INSERT INTO redirects (source,target) VALUES %s ON DUPLICATE KEY UPDATE target=VALUES(target);";
        $c = 0;
        $p = [];
        foreach ($this->redirects as $source => $target) {
            $inserts[] = "(:P{$c}s,:P{$c}t)";
            $p[":P{$c}s"] = $source;
            $p[":P{$c}t"] = $target;
            $c++;
        }
        if (count($inserts)) {
            \DB\DB::F()->exec(sprintf($q, implode(",", $inserts)), $p);
            \DB\errors\MySQLWarn::F(\DB\DB::F());
        }
    }

    protected function get_product_alias_by_article($article) {
        $possible_products = \DB\DB::F()->queryAll("SELECT id FROM catalog__product WHERE source_article=:P ", [":P" => $article]);
        foreach ($possible_products as $row) {
            $product = null;
            try {
                $product = \DataModel\Product\Model\ProductModel::F(intval($row["id"]));
            } catch (\Throwable $e) {
                $product = null;
            }
            if ($product && $this->valid_product($product)) {
                return $product->alias;
            }
        }
        return null;
    }

    protected function valid_product(\DataModel\Product\Model\ProductModel $product) {
        if ($product->enabled) {
            $x = 0;
            foreach ($product->catalogs as $catalog) {/* @var $catalog \DataModel\Product\Model\ProductCatalog */
                $x += $this->valid_catalog($catalog);
            }
            if ($x) {
                return true;
            }
        }
        return false;
    }

    protected function valid_catalog(\DataModel\Product\Model\ProductCatalog $catalog) {
        if (!$this->catalog_tree) {
            $this->catalog_tree = \CatalogTree\CatalogTree::F();
        }
        $node = $this->catalog_tree->get_item_by_id($catalog->id);
        if ($node) {/* @var $node \CatalogTree\CatalogTreeItem */
            if ($node->visible && $node->visible_parents) {
                return 1;
            }
        }
        return 0;
    }

}

url_fix::F()->run();
