<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\TagList;

/**
 * Description of TagList
 *
 * @author eve
 * @property TagListItem[] $items
 */
abstract class TagList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    CONST LANGUAGE_LINK_MODE_MULTABLE = 2; // read from xx__yy__lang_%s table
    CONST LANGUAGE_LINK_MODE_SIMTABLE = 1; // read from xx_strings table whith language key
    CONST LANGUAGE_LIST_MODE_SAMETABLE = 0; // read from same table as child entity

    protected $items;

    protected function __get__items() {
        return $this->items;
    }

    protected function __construct($id = null) {
        $this->items = [];
        if ($id) {
            $this->load($id);
        }
    }

    /**
     * 
     * @param mixed $id
     * @return \static
     */
    public static function F($id = null) {
        return new static($id);
    }

    /**
     * 
     * @param mixed $id
     * @return $this
     */
    public function load($id, \Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null) {
        $language?0:$language = \Language\LanguageList::F()->get_current_language();
        $default_language?0:$default_language = \Language\LanguageList::F()->get_default_language();
        $squery = null;
        $params = [":P" => $id];
        if ($this->get_language_link_mode() === static::LANGUAGE_LINK_MODE_MULTABLE) {
            $query = "SELECT `{$this->get_list_column()}` as `id`,
                COALESCE(LV1.`{$this->get_name_column()}`,LV2.`{$this->get_name_column()}`) as `name`,
                A.`{$this->get_sort_column()}` as `sort`
                FROM `{$this->get_list_table()}` A 
                JOIN `{$this->get_linked_table()}` B ON(A.`{$this->get_list_column()}` = B.`{$this->get_linked_table_key_column()}`)
                LEFT JOIN `{$this->get_strings_table_tpl()}` LV1 ON(LV1.`{$this->get_strings_table_key()}`=B.`{$this->get_linked_table_key_column()}`)
                LEFT JOIN `{$this->get_strings_table_tpl()}` LV2 ON(LV2.`{$this->get_strings_table_key()}`=B.`{$this->get_linked_table_key_column()}`)
                WHERE A.`{$this->get_key_column()}` = :P
                ORDER BY A.`{$this->get_sort_column()}`;                    
                ";
            $squery = sprintf($query, $language, $default_language);
        } else if ($this->get_language_link_mode() === static::LANGUAGE_LIST_MODE_SAMETABLE) {
            $squery = "SELECT `{$this->get_list_column()}` as `id`,
                B.`{$this->get_name_column()}` as `name`,
                A.`{$this->get_sort_column()}` as `sort`
                FROM `{$this->get_list_table()}` A 
                JOIN `{$this->get_linked_table()}` B ON(A.`{$this->get_list_column()}` = B.`{$this->get_linked_table_key_column()}`)                                
                WHERE A.`{$this->get_key_column()}` = :P
                ORDER BY A.`{$this->get_sort_column()}`;                    
                ";
        } else if ($this->get_language_link_mode() === static::LANGUAGE_LINK_MODE_SIMTABLE) {
            $query = "SELECT `{$this->get_list_column()}` as `id`,
                COALESCE(LV1.`{$this->get_name_column()}`,LV2.`{$this->get_name_column()}`) as `name`,
                A.`{$this->get_sort_column()}` as `sort`
                FROM `{$this->get_list_table()}` A 
                JOIN `{$this->get_linked_table()}` B ON(A.`{$this->get_list_column()}` = B.`{$this->get_linked_table_key_column()}`)
                LEFT JOIN `{$this->get_strings_table_name()}` LV1 ON(LV1.`{$this->get_strings_table_key()}`=B.`{$this->get_linked_table_key_column()}` AND LV1.`{$this->get_language_column()}` = '%s')
                LEFT JOIN `{$this->get_strings_table_name()}` LV2 ON(LV2.`{$this->get_strings_table_key()}`=B.`{$this->get_linked_table_key_column()}` AND LV2.`{$this->get_language_column()}` = '%s')                
                WHERE A.`{$this->get_key_column()}` = :P
                ORDER BY A.`{$this->get_sort_column()}`";
            $squery = sprintf($query, $language, $default_language);
        }
        
        $rows = \DB\DB::F()->queryAll($squery, $params);
        foreach ($rows as $row) {
            try {
                $this->items[] = TagListItem::F($row);
            } catch (\Throwable $e) {
                
            }
        }



        return $this;
    }

    /** main list table - parent_id,child_id,sort */
    protected abstract function get_list_table(): string;

    /** which column in list is aparent identifier */
    protected abstract function get_key_column(): string;

    /** which column in a list is a child identifier */
    protected abstract function get_list_column(): string;

    /** which column in a list is a sort column */
    protected function get_sort_column(): string {
        return 'sort';
    }

    /** name of child entity main table */
    protected abstract function get_linked_table(): string;

    /** name of childs main table primary key column */
    protected abstract function get_linked_table_key_column(): string;

    /** column in language table, containig name */
    protected abstract function get_name_column(): string;

    /** template name for language table xxx__yyy__lang_%s (MULTABLE MODE ONLY) */
    protected abstract function get_strings_table_tpl(): string;

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    /** where to take strings. see LANG_ constants */
    protected abstract function get_language_link_mode(): int;

    /** strings tabele for SIMTABLE mode */
    protected abstract function get_strings_table_name(): string;

    /** language identifier column for SIMTABLE mode */
    protected abstract function get_language_column(): string;

    /** key in strings table for join (SIMTABLE and MULTABLE modes) */
    protected abstract function get_strings_table_key(): string;
}
