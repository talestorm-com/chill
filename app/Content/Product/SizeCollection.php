<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Product;

/**
 * Итак нужно - список всех размеров дфккщ
 * список всех видимых систем размеров, которые имеют пересечение со списком размеров дфккщ в порядке их сортировки
 * список значений размеров
 * 
 * 
 * @property bool $has_sizes
 * @property bool $has_alters
 * @property \DataModel\CatalogSizeDef\CatalogSizeDefinition[] $defs
 * @property SizeElement $items
 */
class SizeCollection implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    /** @var SizeElement[] */
    protected $items;

    /** @var \DataModel\CatalogSizeDef\CatalogSizeDefinition[] */
    protected $defs;

    protected function __get__has_sizes() {
        return count($this->items) ? true : false;
    }

    protected function __get__has_alters() {
        // return false;
        return count($this->defs) ? true : false;
    }

    protected function __get__defs() {
        return $this->defs;
    }

    protected function __get__items() {
        return $this->items;
    }

    protected function __construct(\DataModel\Product\Model\ProductSizeCollection $avl) {
        $search_ids = [];
        foreach ($avl as $size) {/* @var $size \DataModel\Product\Model\ProductSize */
            if ($size->enabled) {
                $search_ids[] = $size->id;
            }
        }
        if (count($search_ids)) {
            $this->load($search_ids);
        } else {
            $this->items = [];
            $this->defs = [];
        }
    }

    protected function load(array $ids) {
        $tn = "a" . md5(__METHOD__);
        $q = "
            DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
            CREATE TEMPORARY TABLE `{$tn}` (id INT(11) UNSIGNED NOT NULL,PRIMARY KEY(id));
            INSERT INTO `{$tn}` (id) VALUES(" . implode("),(", $ids) . ") ON DUPLICATE KEY UPDATE id=VALUES(id);           
            ";
        \DB\DB::F()->exec($q);
        \DB\errors\MySQLWarn::F(\DB\DB::F());
        $query = "
          SELECT A.id,B.size shop_size, C.alter_size,D.name,D.short_name,D.id sid
            FROM `{$tn}` A JOIN catalog__size__def B ON(A.id=B.id)
            LEFT JOIN catalog__size__alter C ON(C.size_id=A.id)
            LEFT JOIN catalog__size__alter__def D ON(D.id=C.alter_id AND D.visible=1)
            ORDER BY B.size,D.id
        ";
        $rows = \DB\DB::F()->queryAll($query); // фильтр по отсутствующим - если нет нигде???
        $items = [];
        $found_systems = [];
        foreach ($rows as $row) {
            $key = "P{$row['id']}";
            if (!array_key_exists($key, $items)) {
                $items[$key] = SizeElement::F($row);
            }
            if ($row['sid']) {
                $items[$key]->add_alter($row); // потеря порядка? нужен отдельно список?
                $found_systems["P{$row['sid']}"] = intval($row['sid']);
            }
        }
        $this->items = $items;
        $this->defs = [];
        $defs = \DataModel\CatalogSizeDef\CatalogSizeDefVoc::F();
        foreach ($found_systems as $sys_id) {
            $def = $defs->get_by_id($sys_id);
            /* @var $def \DataModel\CatalogSizeDef\CatalogSizeDefinition */
            if ($def && $def->valid && $def->visible) {
                $this->defs[] = $def;
            }
        }
    }

    public function has_alters(): bool {
        return count($this->defs) ? true : false;
    }

    public function s() { //попробуем так
        $ct = "tmp_size_search";
        $qu = "
            DROP TEMPORARY TABLE IF EXISTS `{$ct}`;
            CREATE TEMPORARY TABLE `{$ct}` (id INT(11) UNSIGNED NOT NULL,PRIMARY KEY(id));
            INSERT INTO `{$ct}` (id) VALUES(7,8,9,13,18,19) ON DUPLICATE KEY UPDATE id=VALUES(id);
            SELECT A.id,B.size larro_size C.alter_size,D.name,D.short_name
            FROM `{$ct}` A JOIN catalog__size__def B ON(A.id=B.id)
            LEFT JOIN catalog__size__alter C ON(C.size_id=A.id)
            LEFT JOIN catalog__size__alter__def D ON(D.id=C.alter_id AND D.visible=1)
            
            ;
            
            ";
    }

    public static function F(\DataModel\Product\Model\ProductSizeCollection $sizes) {
        return new static($sizes);
    }

    public function marshall() {
        return [
            'items' => $this->t_default_marshaller_marshall_array(array_values($this->items)),
            'defs' => $this->t_default_marshaller_marshall_array(array_values($this->defs))
        ];
        
    }

}
