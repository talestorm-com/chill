<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of LanguageTablesManager
 *
 * @author eve
 */
final class LanguageTablesManager {

    const ETHALON_LANGUAGE = 'ru';

    private static $instance;
    private $tables;

    private function __construct() {
        static::$instance = $this;
        $this->reload();
    }

    /**
     * 
     * @return $this
     */
    public function reload() {
        $rows = \DB\DB::F()->queryAllIndex("SHOW TABLES;");
        $this->tables = [];

        foreach ($rows as $row) {
            $table_name = \Filters\FilterManager::F()->apply_chain($row[0], ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            if ($table_name) {
                $this->tables[] = $table_name;
            }
        }
        return $this;
    }

    public function mk_language_tables(string $language, $force = false) {
        $ethalon_tables = [];
        $x = static::ETHALON_LANGUAGE;
        $regex = "/^(?P<table>.{1,})lang_{$x}$/i";
        foreach ($this->tables as $table) {
            $m = [];
            if (preg_match($regex, $table, $m)) {
                $ethalon_tables[] = "{$m["table"]}";
            }
        }
        $tables_to_create = [];
        foreach ($ethalon_tables as $source_name) {
            $target_table = "{$source_name}lang_{$language}";
            $source_table = "{$source_name}lang_{$x}";
            if (!array_key_exists($target_table, $this->tables) || $force) {
                $tables_to_create[$target_table] = $source_table;
            }
        }
        if (count($tables_to_create)) {
            $builder = \DB\SQLTools\SQLBuilder::F();
            foreach ($tables_to_create as $target => $source) {
                if ($force) {
                    $builder->push("DROP TABLE IF EXISTS `{$target}`;");
                }
                
                if ($force || array_search($target, $this->tables, true) === false) {
                    $source_sql = \DB\DB::F()->queryRowIndex("SHOW CREATE TABLE `{$source}`;");
                    $target_sql = rtrim(str_ireplace("lang_{$x}", "lang_{$language}", $source_sql[1]), ";");
                    $builder->push("{$target_sql};");
                }
            }
            $builder->execute();
        }
        $this->reload();
    }

    public function remove_language_tables(string $language) {
        if ($language === LanguageList::F()->get_default_language() || $language === static::ETHALON_LANGUAGE) {
            \Errors\common_error::RF("cant remove default language");
        }
        $this->reload();
        $tables_to_remove = [];
        $rx = "/lang_{$language}$/i";
        foreach ($this->tables as $table_name) {
            if (preg_match($rx, $table_name)) {
                $tables_to_remove[] = $table_name;
            }
        }
        $b = \DB\SQLTools\SQLBuilder::F();
        foreach ($tables_to_remove as $table_name) {
            $b->push("DROP TABLE IF EXISTS `{$table_name}`;");
        }
        if (!$b->empty) {
            $b->execute();
        }
        $this->reload();
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

}
