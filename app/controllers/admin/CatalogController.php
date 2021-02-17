<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class CatalogController extends AbstractAdminController {

    public function actionIndex() {
        $this->render_view("admin", "list");
    }

    //<editor-fold defaultstate="collapsed" desc="tree operations">
    protected function API_get_tree() {
        $this->out->add('catalog_tree', \CatalogTree\CatalogTree::F());
    }

    protected function API_get_group(int $rid = null) {
        $id = $rid ? $rid : $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? FALSE : \Errors\common_error::R("invalid request");
        $row = \DB\DB::F()->queryRow("SELECT * FROM catalog__group WHERE id=:Pid", [":Pid" => $id]);
        $row ? FALSE : \Errors\common_error::R("not found");
        $row['path'] = \CatalogTree\CatalogTreeSinglet::F()->tree->get_item_by_id($id)->get_parent_path("\\");
        $row['import_processor'] = \Filters\FilterManager::F()->apply_chain($row['import_processor'], ['Strip', 'Trim', 'NEString', 'CSVArray', 'ArrayOfStrippedNEString', "NEArray", 'DefaultEmptyArray']);
        $row['properties'] = \Content\Catalog\PropertyCollection::F()->load_from_database($row['id'])->marshall();
        $this->out->add('data', $row);
        $this->API_get_group_metadata();
    }

    protected function API_get_group_metadata() {
        $this->out->add("import_processors", \DataImport\ImportProcessor\ImportProcessorManager::F(), "meta");
    }

    protected function API_post_group() {
        $x = \DataMap\GPDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'DefaultNull']);
        $x && is_array($x) ? false : \Errors\common_error::R("invalid request");
        $group_data = $this->FM->apply_filter_array($x, $this->get_group_filters());

        $this->FM->raise_array_error($group_data);
        if (!$group_data['alias']) {
            $group_data['alias'] = \Helpers\Helpers::translit($group_data['name']);
        }
        $group_data['alias'] = \Helpers\Helpers::uniqueAlias("catalog__group", $group_data['alias'], $group_data['id']);
        $builder = \DB\SQLTools\SQLBuilder::F();
        $tn = "@a" . md5(__METHOD__);
        if ($group_data['id']) {
            $builder->push("SET {$tn} = :P{$builder->c}id;");
            $builder->push_param(":P{$builder->c}id", $group_data['id']);
            $builder->push("UPDATE `catalog__group` SET 
                parent_id=:P{$builder->c}parent_id,
                sort_order=:P{$builder->c}sort_order,
                name=:P{$builder->c}name,visible=:P{$builder->c}visible,
                alias=:P{$builder->c}alias,guid=:P{$builder->c}guid,
                info=:P{$builder->c}info,
                html_mode=:P{$builder->c}html_mode,
                default_image=:P{$builder->c}default_image,
                import_processor=:P{$builder->c}import_processor,
                terminal=:P{$builder->c}terminal,
                meta_title=:P{$builder->c}meta_title,
                meta_description=:P{$builder->c}meta_description,        
                meta_keywords=:P{$builder->c}meta_keywords,                    
                og_title=:P{$builder->c}og_title,
                og_description=:P{$builder->c}og_description           
                WHERE id={$tn};");
        } else {
            $builder->push("INSERT INTO `catalog__group` (parent_id,sort_order,name,
                visible,alias,guid,info,html_mode,default_image,import_processor,terminal,
                meta_title,meta_description,meta_keywords,og_title,og_description) 
                VALUES(:P{$builder->c}parent_id,:P{$builder->c}sort_order,
                :P{$builder->c}name,:P{$builder->c}visible,:P{$builder->c}alias,
                :P{$builder->c}guid,:P{$builder->c}info,:P{$builder->c}html_mode,
                :P{$builder->c}default_image,:P{$builder->c}import_processor,:P{$builder->c}terminal,
                :P{$builder->c}meta_title,:P{$builder->c}meta_description,:P{$builder->c}meta_keywords,
                :P{$builder->c}og_title,:P{$builder->c}og_description);");
            $builder->push("SET {$tn} = LAST_INSERT_ID();");
        }
        $builder->push_params([
            ":P{$builder->c}parent_id" => $group_data['parent_id'],
            ":P{$builder->c}sort_order" => $group_data['sort_order'],
            ":P{$builder->c}name" => $group_data['name'],
            ":P{$builder->c}visible" => $group_data['visible'],
            ":P{$builder->c}alias" => $group_data['alias'],
            ":P{$builder->c}guid" => $group_data['guid'],
            ":P{$builder->c}info" => $group_data['info'],
            ":P{$builder->c}html_mode" => $group_data['html_mode'],
            ":P{$builder->c}default_image" => $group_data['default_image'],
            ":P{$builder->c}import_processor" => $group_data['import_processor'],
            ":P{$builder->c}terminal" => $group_data["terminal"],
            ":P{$builder->c}meta_title" => $group_data["meta_title"],
            ":P{$builder->c}meta_description" => $group_data["meta_description"],
            ":P{$builder->c}meta_keywords" => $group_data["meta_keywords"],
            ":P{$builder->c}og_title" => $group_data["og_title"],
            ":P{$builder->c}og_description" => $group_data["og_description"],
        ]);
        $properties = \Content\Catalog\PropertyCollection::F();
        $properties->load_from_object_array($group_data["properties"]);
        $properties->save($builder, $tn);
        $new_catalog_id = $builder->execute_transact($tn);
        \CatalogTree\CatalogTree::clear_dependency_beacon();
        $this->API_get_group($new_catalog_id);
    }

    protected function get_group_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'parent_id' => ['IntMore0', 'DefaultNull'],
            'sort_order' => ['AnyInt', 'Default0'],
            'visible' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'html_mode' => ['Boolean', 'DefaultTrue', 'SQLBool'],
            'guid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'alias' => ['Strip', 'Trim', 'NEString', 'Alias', 'Trim', 'NEString', 'DefaultNull'],
            'info' => ['Trim', 'ClearScript', 'NEString', 'DefaultEmptyString'],
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            "import_processor" => ['Strip', 'Trim', 'NEString', 'CSVArray', 'ArrayOfNEString', 'NEArray', 'DefaultEmptyArray', 'CSVJoin', 'NEString', 'DefaultNull'],
            "properties" => ["NEArray", "DefaultEmptyArray"],
            "terminal" => ["Boolean", "DefaultFalse", 'SQLBool'],
            'meta_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'meta_description' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'meta_keywords' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'og_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'og_description' => ['Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

    protected function API_remove_catalog() {
        $id = $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? FALSE : \Errors\common_error::R("invalid request");
        \DB\SQLTools\SQLBuilder::F()
                ->push("DELETE FROM catalog__group WHERE id=:Pid;")
                ->push_param(":Pid", $id)
                ->execute_transact();
        \CatalogTree\CatalogTree::clear_dependency_beacon();
        $this->API_get_tree();
    }

    protected function API_move_group_to() {
        $data_raw = $this->GP->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        $data = \DataMap\CommonDataMap::F()->rebind($data_raw);
        $group = $data->get_filtered('group', ['IntMore0', 'DefaultNull']);
        $to = $data->get_filtered('to', ['IntMore0', 'DefaultNull']);
        $group && $to ? FALSE : \Errors\common_error::R("invalid request");
        $apply_sort = $data->get_filtered("apply_sort", ['Boolean', 'DefaultFalse']);
        $catalog = \CatalogTree\CatalogTree::F(); /* @var $catalog \CatalogTree\CatalogTree */
        $o_group = $catalog->get_item_by_id($group);
        $o_to = $catalog->get_item_by_id($to);
        $o_group ? FALSE : \Errors\common_error::R("moving item not found");
        $o_to ? FALSE : \Errors\common_error::R("target item not found");
        $o_to->is_my_ancestor($o_group) ? \Errors\common_error::R("cant move object inside itself") : false;
        \DB\SQLTools\SQLBuilder::F()
                ->push("UPDATE catalog__group SET parent_id=:Pparent WHERE id=:Pid;")
                ->push_params([":Pid" => $group, ":Pparent" => $to])
                ->execute_transact();
        if ($apply_sort) {
            $this->apply_new_catalog_order($data->get_filtered('new_order', ['NEArray', 'DefaultEmptyArray']));
        }
        \CatalogTree\CatalogTree::clear_dependency_beacon();
        $this->API_get_tree();
    }

    protected function API_move_group_to_root() {
        $data_raw = $this->GP->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        $data = \DataMap\CommonDataMap::F()->rebind($data_raw);
        $group = $data->get_filtered('group', ['IntMore0', 'DefaultNull']);
        $group ? FALSE : \Errors\common_error::R("invalid request");
        $apply_sort = $data->get_filtered("apply_sort", ['Boolean', 'DefaultFalse']);
        $catalog = \CatalogTree\CatalogTree::F(); /* @var $catalog \CatalogTree\CatalogTree */
        $o_group = $catalog->get_item_by_id($group);
        $o_group ? FALSE : \Errors\common_error::R("moving item not found");
        \DB\SQLTools\SQLBuilder::F()
                ->push("UPDATE catalog__group SET parent_id=:Pparent WHERE id=:Pid;")
                ->push_params([":Pid" => $group, ":Pparent" => NULL])
                ->execute_transact();
        if ($apply_sort) {
            $this->apply_new_catalog_order($data->get_filtered('new_order', ['NEArray', 'DefaultEmptyArray']));
        }
        \CatalogTree\CatalogTree::clear_dependency_beacon();
        $this->API_get_tree();
    }

    protected function API_move_group_root() {
        $group = $this->GP->get_filtered('group', ['IntMore0', 'DefaultNull']);
        $group ? FALSE : \Errors\common_error::R("invalid request");
        $catalog = \CatalogTree\CatalogTree::F(); /* @var $catalog \CatalogTree\CatalogTree */
        $o_group = $catalog->get_item_by_id($group);
        $o_group ? FALSE : \Errors\common_error::R("moving item not found");
        \DB\SQLTools\SQLBuilder::F()
                ->push("UPDATE catalog__group SET parent_id=NULL WHERE id=:Pid;")
                ->push_params([":Pid" => $group])
                ->execute_transact();
        \CatalogTree\CatalogTree::clear_dependency_beacon();
        $this->API_get_tree();
    }

    protected function API_change_nodes_order() {
        $new_order = $new_order = $this->GP->get_filtered('new_order', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        $this->apply_new_catalog_order($new_order);
        \CatalogTree\CatalogTree::clear_dependency_beacon();
        $this->API_get_tree();
    }

    protected function apply_new_catalog_order(array $new_catalog_order) {
        $param_pool = \Filters\params\ArrayParamBuilder::B(['ArrayOfInt' => ['count_min' => 2, 'min' => 1]], true);
        $new_order = \Filters\FilterManager::F()->apply_chain($new_catalog_order, ['NEArray', 'ArrayOfInt', 'DefaultNull'], $param_pool->get_param_set_for_property());
        if ($new_order) {
            $tn = "x" . md5(__METHOD__);
            $query = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
                CREATE TEMPORARY TABLE `{$tn}`(id INT(11) UNSIGNED NOT NULL,sort INT(11) NOT NULL,PRIMARY KEY(id))ENGINE=MEMORY;
                INSERT INTO `{$tn}`(id,sort) VALUES %s ON DUPLICATE KEY UPDATE sort=VALUES(sort);
                ";
            $inserts = [];
            $counter = 0;
            $params = [];
            for ($i = 0; $i < count($new_order); $i++) {
                $inserts[] = "(:P{$counter}id,:P{$counter}sort)";
                $params[":P{$counter}id"] = $new_order[$i];
                $params[":P{$counter}sort"] = $i * 10;
                $counter++;
            }
            \DB\DB::F()->exec(sprintf($query, implode(",", $inserts)), $params);
            \DB\errors\MySQLWarn::F(\DB\DB::F());
            \DB\SQLTools\SQLBuilder::F()->push("UPDATE catalog__group A JOIN `{$tn}` B ON(A.id=B.id) SET A.sort_order=B.sort;")->execute_transact();
        }
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="product operations">    

    protected function get_shop_interface(): \ModulesSupport\Products\IModuleProducts {
        return \DataModel\Product\ProductModule::F();
    }

    protected function API_get_list() {
        // селектор до низу, с выборкой - уником   
        $this->get_shop_interface()->advt_list_products($this->out);
    }

    protected function API_product_selector() {
        $this->get_shop_interface()->advt_list_products($this->out);
    }

    protected function API_post_product() {
        $this->get_shop_interface()->editor_post_product($this->out);
    }

    protected function API_get_product() {
        $this->get_shop_interface()->editor_get_product($this->out);
    }

    protected function API_enum_product_ids_of() {
        $this->get_shop_interface()->list_products_of_catgeory($this->out, $this->GP);
    }

    protected function API_link_products() {
        $data_raw = $this->GP->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        $data = \DataMap\CommonDataMap::F()->rebind($data_raw);
        $this->get_shop_interface()->link_products($this->out, $data);
    }

    protected function API_move_products() {
        $data_raw = $this->GP->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        $data = \DataMap\CommonDataMap::F()->rebind($data_raw);
        $this->get_shop_interface()->move_products($this->out, $data);
    }

    protected function API_unlink_products() {
        $data_raw = $this->GP->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        $data = \DataMap\CommonDataMap::F()->rebind($data_raw);
        $this->get_shop_interface()->unlink_products($this->out, $data);
    }

    protected function API_remove_products() {
        $data_raw = $this->GP->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        $data = \DataMap\CommonDataMap::F()->rebind($data_raw);
        $this->get_shop_interface()->remove_products($this->out, $data);
    }

    //</editor-fold>

    protected function API_apply_sort() {
        $data_raw = $this->GP->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        $this->get_shop_interface()->apply_sort($this->out, $data_raw);
    }

    protected function actionUpdateImages() {
        $this->render_view("admin", "parsed_images");
    }

    protected function API_UpdateImagesFromParser() {
        $DB = \DB\DB::F();
        $offset = $this->GP->get_filtered("offset", ['IntMore0', "Default0"]);
        if ($offset === 0) {
            \ImageFly\ImageFly::F()->clear_media_context("product");
        }
        $limit = 50;
        $records_to_check = $this->GP->get_filtered("records_to_check", ['IntMore0', 'DefaultNull']);
        if (!$records_to_check) {
            $records_to_check = $DB->queryScalari("SELECT COUNT(*) FROM catalog__product WHERE source_article IS NOT NULL;");
            $this->out->add("records_to_check", $records_to_check);
        }
        $query = "SELECT id,source_article FROM catalog__product WHERE source_article IS NOT NULL ORDER BY id LIMIT {$limit} OFFSET {$offset} ;";
        $rows_to_process = $DB->queryAll($query);
        if (count($rows_to_process)) {
            for ($i = 0; $i < count($rows_to_process); $i++) {
                $rows_to_process[$i]["base_name"] = md5($rows_to_process[$i]['source_article']);
            }
            $base_path = \Config\Config::F()->LOCAL_TMP_PATH . "image_parser" . DIRECTORY_SEPARATOR;
            $rows_cnt = count($rows_to_process);
            $images = 0;
            $default_images = [];
            foreach ($rows_to_process as $row) {
                $product_id = intval($row["id"]);
                $c = 0;
                $file = "{$base_path}{$row['base_name']}.{$c}.png";
                while (file_exists($file) && is_readable($file) && is_file($file)) {
                    $image_name = \ImageFly\ImageFly::F()->add_image_from_file($file, "product", (string) $product_id);
                    if ($c === 0) {
                        $default_images[] = [$product_id, $image_name];
                    }
                    $images++;
                    $c++;
                    $file = "{$base_path}{$row['base_name']}.{$c}.png";
                }
            }
            if (count($default_images)) {
                $b = \DB\SQLTools\SQLBuilder::F();
                foreach ($default_images as $def) {
                    $b->push("UPDATE catalog__product SET default_image=:P{$b->c}i WHERE id=:P{$b->c}n;");
                    $b->push_params([
                        ":P{$b->c}i" => $def[1],
                        ":P{$b->c}n" => $def[0],
                    ]);
                    $b->inc_counter();
                }
                $b->execute();
            }
            $this->out->add("next_offset", $offset + $limit);
            $toffset = $offset + $limit;
            $this->out->add("action", "redirect");
            $this->out->add("log", "processed {$rows_cnt} rows ({$toffset} of {$records_to_check}), linked {$images} images");
        } else {
            $this->out->add("log", "Очистка кеша и завершение");
            \DataModel\Product\Model\ProductModel::RESET_CACHE();
            $this->out->add("action", "done");
        }
    }

}
