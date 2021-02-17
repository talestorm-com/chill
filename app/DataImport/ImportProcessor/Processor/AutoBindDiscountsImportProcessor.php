<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\ImportProcessor\Processor;

class AutoBindDiscountsImportProcessor extends \DataImport\ImportProcessor\AbstractImportProcessor {

    public function before_run(\CatalogTree\CatalogTreeItem $node): string {
        return sprintf("Обработка процессора \"%s\" на узле \"%s\"", static::get_display_name(), $node->get_path(" \\ "));
    }

    public function after_run(\CatalogTree\CatalogTreeItem $node): string {
        return "Завершено.";
    }

    public function run(\CatalogTree\CatalogTreeItem $node): string {
        $node_id = $node->id;
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("DELETE FROM catalog__product__group WHERE group_id=:P;");
        $b->push_param(":P", $node_id);
        $b->push("INSERT INTO catalog__product__group (product_id,group_id)
            SELECT A.id,:P FROM catalog__product__price A WHERE 
            (A.discount_retail IS NOT NULL AND A.discount_retail>0) OR
            (A.discount_gross IS NOT NULL AND A.discount_gross>0);            
            ");
        $b->execute_transact();
        return "";
    }

    public static function get_display_name(): string {
        return "Автобинд скидок";
    }

    public static function get_processor_description(): string {
        return "Автоматически помещает в катешорию товары со скидками";
    }

}
