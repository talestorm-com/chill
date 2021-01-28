<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\CatalogSizeDef;

class Writer {

    /** @var WriterItem[] */
    protected $items;

    protected function __construct(array $items = null) {
        if ($items) {
            $this->import($items);
        }
    }

    /**
     * 
     * @param array $data
     * @return $this
     */
    protected function import(array $data) {
        $this->items = [];
        foreach ($data as $row) {
            $item = WriterItem::F($row);
            $item && $item->valid ? $this->items[] = $item : 0;
        }
        return $this;
    }

    /**
     * 
     * @param array $items
     * @return \DataModel\CatalogSizeDef\Writer
     */
    public static function F(array $items = null): Writer {
        return new static($items);
    }

    public function update() {
        $tn = "a" . md5(__METHOD__);
        // сначала - удалим несуществующие
        $updates = []; /* @var $updates WriterItem[] */
        $inserts = []; /* @var $inserts WriterItem[] */
        $aliases = []; /* @var $aliases WriterItemAlias[] */
        $exists_ids = [];
        foreach ($this->items as $item) {
            if ($item->is_new) {
                $inserts[] = $item;
            } else {
                $updates[] = $item;
                $exists_ids[] = $item->id;
            }
            $item->export_aliases($aliases);
        }
        $query_remove = "DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
            DROP TEMPORARY TABLE IF EXISTS `{$tn}a`;
            CREATE TEMPORARY TABLE `{$tn}`(id INT(11) UNSIGNED, PRIMARY KEY(id))ENGINE=MEMORY;
            CREATE TEMPORARY TABLE `{$tn}a` (guid VARCHAR(80),size_def_id INT(11) UNSIGNED,size VARCHAR(255) NOT NULL,PRIMARY KEY(guid,size_def_id))ENGINE=MEMORY;
            ";
        
        if (count($exists_ids)) {
            $query_remove .= "INSERT INTO `{$tn}`(id) VALUES(" . implode("),(", $exists_ids) . ") ON DUPLICATE KEY UPDATE id=VALUES(id);";
        }
        
        \DB\DB::F()->exec($query_remove);
        \DB\errors\MySQLWarn::F(\DB\DB::F());        
        $b = \DB\SQLTools\SQLBuilder::F(\DB\DB::F());
        $b->push("DELETE A.* FROM catalog__size__def A LEFT JOIN `{$tn}` B ON(A.id=B.id) WHERE B.id IS NULL;");
        foreach ($updates as $update_row) {
            $b->push("UPDATE catalog__size__def SET guid=:P{$b->c}guid,size=:P{$b->c}size WHERE id=:P{$b->c}id;");
            $b->push_params([
                ":P{$b->c}id" => $update_row->id,
                ":P{$b->c}guid" => $update_row->guid,
                ":P{$b->c}size" => $update_row->size,
            ]);
            $b->inc_counter();
        }
        $ri = [];
        foreach ($inserts as $insert_row) {
            $ri[] = "(:P{$b->c}guid,:P{$b->c}size)";
            $b->push_params([
                ":P{$b->c}guid" => $insert_row->guid,
                ":P{$b->c}size" => $insert_row->size,
            ]);
            $b->inc_counter();
        }
        if (count($ri)) {
            $b->push("INSERT INTO catalog__size__def (guid,size) VALUES " . implode(",", $ri).";");
        }
        $ali = [];
        
        foreach ($aliases as $alias_row) {
            $ali[] = "(:P{$b->c}guid,:P{$b->c}size_def_id,:P{$b->c}size)";
            $b->push_params([
                ":P{$b->c}guid" => $alias_row->parent_guid,
                ":P{$b->c}size_def_id" => $alias_row->id,
                ":P{$b->c}size" => $alias_row->size,
            ]);
            $b->inc_counter();
        }
        
        if (count($ali)) {            
            $b->push("INSERT INTO `{$tn}a` (guid,size_def_id,size) VALUES " . implode(",", $ali) . " ON DUPLICATE KEY UPDATE size=VALUES(size);");
        }
        $b->push("DELETE  FROM catalog__size__alter;"); // альтеры просто перезаписываем
        // delete obsolete aliases
        $b->push("INSERT INTO catalog__size__alter (size_id,alter_id,alter_size)
            SELECT B.id,C.id,A.size FROM `{$tn}a` A JOIN catalog__size__def B ON(B.guid=A.guid) JOIN catalog__size__alter__def C ON(C.id=A.size_def_id);
            ");


        $b->execute_transact();
       // var_dump(\DB\DB::F()->queryAll("SELECT * FROM `{$tn}`"));
       // var_dump(\DB\DB::F()->queryAll("SELECT * FROM `{$tn}a`"));
        //var_dump(\DB\DB::F()->queryAll("SELECT B.id,C.id m,A.size FROM `a8d867b98d2b345989e6b142ea19b5eaea` A JOIN catalog__size__def B ON(B.guid=A.guid) JOIN catalog__size__alter__def C ON(C.id=A.size_def_id);"));
        //var_dump($b->sql);
        //die();
        CatalogSizeDefVoc::RESET_CACHE();
    }

}
