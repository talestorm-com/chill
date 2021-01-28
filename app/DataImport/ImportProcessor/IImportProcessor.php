<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\ImportProcessor;

interface IImportProcessor {

    public static function get_class_name(): string;

    public static function get_display_name(): string;

    public static function get_processor_description(): string;

    public static function instance(): IImportProcessor;

    public function before_run(\CatalogTree\CatalogTreeItem $node): string;

    public function run(\CatalogTree\CatalogTreeItem $node): string;

    public function after_run(\CatalogTree\CatalogTreeItem $node): string;
}
