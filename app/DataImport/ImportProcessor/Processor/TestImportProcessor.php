<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\ImportProcessor\Processor;

class TestImportProcessor extends \DataImport\ImportProcessor\AbstractImportProcessor {

    public function run(\CatalogTree\CatalogTreeItem $node): string {
        return "do nothing with node \"{$node->name}\"";
    }

    public static function get_display_name(): string {
        return "test";
    }

    public static function get_processor_description(): string {
        return "test processor. does nothing";
    }

}
