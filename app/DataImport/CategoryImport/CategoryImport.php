<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\CategoryImport;

class CategoryImport {

    protected $var_counter = 0;
    protected $default_category_parent = 0;
    protected $default_visible_state = null;
    protected $xls_name = null;

    protected function generate_variable_name() {
        $this->var_counter++;
        return implode("", ["@a", md5(__METHOD__), "b{$this->var_counter}"]);
    }

    protected function get_default_category_parent() {
        if ($this->default_category_parent === 0) {
            $this->default_category_parent = \DataMap\GPDataMap::F()->get_filtered("default_parent", ['IntMore0', 'DefaultNull']);
        }
        return $this->default_category_parent;
    }

    protected function get_default_visible_state() {
        if ($this->default_visible_state === null) {
            $this->default_visible_state = (!\DataMap\GPDataMap::F()->get_filtered('disable_new_categories', ['Boolean', 'DefaultFalse'])) ? 1 : 0;
        }
        return $this->default_visible_state;
    }

    protected function createCategoryAlias(ImportedCategory $c): string {
        return uniqid($c->alias);
    }

    protected function __construct(string $file_name) {
        $this->xls_name = $file_name;
    }

    public static function F(string $file_name): CategoryImport {
        return new static($file_name);
    }

    protected function process_slice($offset, $count, $max_letter, array &$out, array &$excluder) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadFilter(\DataImport\Common\SliceFilter::F($offset, $count));
        $book = $reader->load($this->xls_name);
        unset($reader);
        $sheets = array_values($book->getAllSheets());
        count($sheets) ? false : \Errors\common_error::R("no sheets in file");
        $ws = $sheets[0];
        /* @var $ws \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet */
        //cellmap: uid,article,name,full_name,ed,parent,group,color,size,price_opt,price_rozn,consists,description,opt_old,rozn_old,discount_opt,discount_rozn
        $categories = [];
        $max = $ws->getHighestDataRow();       
        $max_letter = $ws->getHighestDataColumn();        
        $data_raw = $ws->rangeToArray("A{$offset}:{$max_letter}{$max}", NULL, TRUE, FALSE);
        $cd = \DataMap\CommonDataMapIndex::F();  /* @var $cd \DataMap\CommonDataMapIndex  */
        $cd->set_keys(['uid', 'article', 'name', 'full_name', 'ediz', 'parent', 'group', 'color', 'size', 'price_opt', 'price_ret', 'consists', 'info',
            'price_opt_old', 'price_ret_old', 'discount_opt', 'discount_ret']);
        $lines = 0;
        foreach ($data_raw as $raw_row) {
            $lines++;
            $row = $cd->rebind($raw_row);
            $uid = $row->get_filtered('uid', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            if ($uid) {
                $is_category = $row->get_filtered('group', ["Trim", "NEString", 'Boolean', 'DefaultFalse']);
                if ($is_category) {
                    $category = ImportedCategory::F($row);
                    $category && $category->valid ? $categories[] = $category : 0;
                } else {
                    $article = $row->get_filtered('article', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
                    if ($article) {
                        $parent = $row->get_filtered('parent', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
                        if (!array_key_exists("A{$parent}", $excluder)) {
                            $excluder["A{$parent}"] = [];
                        }
                        $excluder["A{$parent}"][] = $article;
                    }
                }
            }
        }
        $out = array_merge($out, $categories);
        $book->disconnectWorksheets();
        unset($sheets);
        unset($ws);
        unset($book);
        unset($categories);
        return $lines;
    }

    public function run() {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $wksheet_info = $reader->listWorksheetInfo($this->xls_name);

        $max_letter = $wksheet_info[0]['lastColumnLetter'];
        $max_row_number = intval($wksheet_info[0]['totalRows']);
        $found_categories = [];
        $categories_with_products = [];
        // товары тоже являются категориями, но  их нужно отделить 
        // - но только в том случае, если имя категории является префиксом к имени товара из этой категории
        // для этой цели заносим товары и родительские категории в отдельный список
        // (uid категории / список имен)
        // если есть совпадение хотя бы с одним - это категория-товар
        $offset = 2;
        $index = 0;
        $total = 0;
        while (($readed = $this->process_slice($index + $offset, 5000, $max_letter, $found_categories, $categories_with_products)) > 0) {
            set_time_limit(40);
            $index += $readed;
            $total += $readed;
            if ($total > $max_row_number) {
                break;
            }            
        }
        // var_dump($categories_with_products);die(__FILE__.__LINE__);
        $categories = $this->normalize_categories($found_categories, $categories_with_products);
        unset($found_categories);
        unset($categories_with_products);
        /* @var $categories ImportedCategory[] */
        /* @var $flat ImportedCategory[] */
        // пойдем последовательно - будем создавать категории в том порядке, в котором они должны быть
        $existed = \CatalogTree\CatalogTree::F(); /* @var $existed \CatalogTree\CatalogTree */ // берем самую свежую версию
       // var_dump($existed);       
        $inserts_vars = [];
        $b = \DB\SQLTools\SQLBuilder::F();
        echo "creating query";        
        $this->createCategoriesQuery($categories, $b, $existed, $inserts_vars);        
        echo "query created";                
        $b->execute_transact();
        \CatalogTree\CatalogTree::clear_dependency_beacon();
    }

    protected function createCategoriesQuery(array $categories, \DB\SQLTools\SQLBuilder $b, \CatalogTree\CatalogTree $tree, array &$inserts_vars) {                              
        /* @var $categories ImportedCategory[] */
        foreach ($categories as $category) {                        
            $existed = $tree->get_item_by_guid($category->uid);                     
            if ($existed) {
                $parent_block = "";
                //установить новую родительскую категорию                
                $existed_parent = $category->parent ? $tree->get_item_by_guid($category->parent) : null; /* @var $existed_parent \CatalogTree\CatalogTreeItem */                
                if ($existed_parent) {
                    $parent_block = "parent_id=:P{$b->c}parent_id, ";
                    $b->push_param(":P{$b->c}parent_id", $existed_parent->id);
                } else if ($category->parent && array_key_exists($category->parent, $inserts_vars)) {
                    $parent_block = "parent_id={$inserts_vars[$category->parent]}, ";
                } else {
                    $parent_block = "parent_id=:P{$b->c}parent_id, ";
                    $b->push_param(":P{$b->c}parent_id", $this->get_default_category_parent());
                }
                $b->push("UPDATE catalog__group SET {$parent_block} name=:P{$b->c}name WHERE id=:P{$b->c}id;");
                $b->push_params([
                    ":P{$b->c}name" => $category->name,
                    ":P{$b->c}id" => $existed->id,
                ]);
            } else {
                $parent_block = "NULL";
                $existed_parent = $category->parent ? $tree->get_item_by_guid($category->parent) : null; /* @var $existed_parent \CatalogTree\CatalogTreeItem */
                if ($existed_parent) {
                    $parent_block = ":P{$b->c}Aparent_id";
                    $b->push_param(":P{$b->c}Aparent_id", $existed_parent->id);
                } else if ($category->parent && array_key_exists($category->parent, $inserts_vars)) {
                    $parent_block = "{$inserts_vars[$category->parent]}";
                } else {
                    $parent_block = ":P{$b->c}Bparent_id";
                    $b->push_param(":P{$b->c}Bparent_id", $this->get_default_category_parent());
                }
                $b->push("INSERT INTO catalog__group(parent_id,name,visible,alias,guid,info)
                    VALUES({$parent_block},:P{$b->c}name,{$this->get_default_visible_state()},:P{$b->c}alias,:P{$b->c}guid,:P{$b->c}info);
                    ");
                $var_name = $this->generate_variable_name();
                $inserts_vars[$category->uid] = $var_name;
                $b->push("SET {$var_name} = LAST_INSERT_ID();");
                $b->push_params([
                    ":P{$b->c}name" => $category->name,
                    ":P{$b->c}alias" => $this->createCategoryAlias($category),
                    ":P{$b->c}guid" => $category->uid,
                    ":P{$b->c}info" => $category->info,
                ]);
            }
            $b->inc_counter();
            $this->createCategoriesQuery($category->childs, $b, $tree, $inserts_vars);
        }
    }

    protected function enflate_categories(array $categories, array &$out) {
        foreach ($categories as $category) {/* @var $category ImportedCategory */
            $out[] = $category;
            if ($category->length) {
                $this->enflate_categories($category->childs, $out);
            }
        }
    }

    protected function normalize_categories(array $categories, array $products) {
        $flat_categories = $this->filter_product_categories($categories, $products);
        unset($products);
        unset($categories);
        /* @var $flat_categories ImportedCategory[] */
        /* @var $index ImportedCategory[] */
        /* @var $root ImportedCategory[] */
        $index = [];
        foreach ($flat_categories as $category) {
            $index[$category->category_key] = $category;
        }
        unset($flat_categories);
        $root = [];
        foreach ($index as $key => $category) {
            if ($category->parent) {
                if (array_key_exists($category->parent_key, $index)) {
                    $index[$category->parent_key]->add_child($category);
                }
            } else {
                $root[] = $category;
            }
        }
        return $root;
    }

    protected function filter_product_categories(array $cat, array $prod): Array {
        $result = [];
        foreach ($cat as $category) {/* @var $category ImportedCategory */
            if (array_key_exists($category->category_key, $prod) && is_array($prod[$category->category_key]) && count($prod[$category->category_key])) {// если в категории есть товары
                $skip = false;
                foreach ($prod[$category->category_key] as $article) {
                    if (preg_match("/(:?^|\s)" . preg_quote($article, "/") . "(?:$|\D)/i", $category->name)) {
                        $skip = true;
                        break;
                    }
                }
                if ($skip) {
                    continue;
                }
            }
            $result[] = $category;
        }
        return $result;
    }

}
