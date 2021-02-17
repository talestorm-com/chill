<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\ImportProcessor;

abstract class AbstractImportProcessor implements IImportProcessor {

    public static function get_class_name(): string {
        return get_called_class();
    }

    public static function instance(): IImportProcessor {
        return new static();
    }

    public function before_run(\CatalogTree\CatalogTreeItem $node): string {
        $cs = static::get_display_name();
        return "running processor \"{$cs}\" on node \"{$node->name}\"";
    }

    public function after_run(\CatalogTree\CatalogTreeItem $node): string {
        $cs = static::get_display_name();
        return "finish processor \"{$cs}\" on node \"{$node->name}\"";
    }

}
