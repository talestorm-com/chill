<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ContentBlock;

/**
 * @property int $id 
 * @property string $alias 
 * @property bool $editor 
 * @property string $comment 
 * @property string $text 
 * @property string $version
 * @property bool $empty
 */
class ContentBlock extends \Content\Content {

    use \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="const">
    const CACHE_DEP_BEACON = "content_block";
    const LOAD_MODE_DB = "database";
    const LOAD_MODE_POST = "post";

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $alias;

    /** @var bool */
    protected $editor;

    /** @var string */
    protected $comment;

    /** @var string */
    protected $text;

    /** @var string */
    protected $version;

    /** @var bool */
    protected $empty = true;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    /** @return bool */
    protected function __get__editor() {
        return $this->editor;
    }

    /** @return string */
    protected function __get__comment() {
        return $this->comment;
    }

    /** @return string */
    protected function __get__text() {
        return $this->text;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    protected function __get__empty() {
        return $this->empty;
    }

    //</editor-fold>

    protected function __construct() {
        $this->version = static::get_file_ver();
    }

    protected static function get_file_ver() {
        return md5(implode(":", [__CLASS__, filemtime(__FILE__)]));
    }

    protected static function cache_id(string $alias): string {
        return implode(":", [__CLASS__, $alias]);
    }

    public function load(string $alias, \DB\IDBAdapter $adapter = null) {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $row = $adapter->queryRow("SELECT *,content as `text` FROM content_block WHERE alias=:Palias;", [":Palias" => $alias]);
        if ($row) {
            $this->import_props($row, null, static::LOAD_MODE_DB);
            $this->empty = false;
        } else {
            $this->empty = true;
        }
    }

    public function load_by_id(int $id, \DB\IDBAdapter $adapter = null) {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $row = $adapter->queryRow("SELECT *,content as `text` FROM content_block WHERE id=:Pid;", [":Pid" => $id]);
        if ($row) {
            $this->import_props($row, null, static::LOAD_MODE_DB);
            $this->empty = false;
        } else {
            $this->empty = true;
        }
    }

    public function load_from_datamap(\DataMap\IDataMap $map) {
        $this->import_props_datamap($map, null, static::LOAD_MODE_POST);
    }

    /**
     * 
     * @param \DB\IDBAdapter $adapter
     * @return int  id of saved block
     */
    public function save(\DB\IDBAdapter $adapter = null): int {
        $b = \DB\SQLTools\SQLBuilder::F($adapter);
        $tn = "@a" . md5(__METHOD__);
        if ($this->id) {
            $b->push("SET {$tn} = :P{$b->c}id;");
            $b->push_param(":P{$b->c}id", $this->id);
            $b->push("UPDATE content_block SET alias=:P{$b->c}alias,editor=:P{$b->c}editor,comment=:P{$b->c}comment,`content`=:P{$b->c}text WHERE id={$tn};");
        } else {
            $b->push("INSERT into content_block (alias,editor,comment,`content`) VALUES(:P{$b->c}alias,:P{$b->c}editor,:P{$b->c}comment,:P{$b->c}text);");
            $b->push("SET {$tn} = LAST_INSERT_ID();");
        }
        $b->push_params([
            ":P{$b->c}alias" => $this->alias, ":P{$b->c}editor" => $this->editor ? 1 : 0, ":P{$b->c}comment" => $this->comment, ":P{$b->c}text" => $this->text
        ]);
        $ret = $b->execute_transact($tn);
        static::RESET_CACHE();
        return $ret;
    }

    public static function RESET_CACHE() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEP_BEACON)->reset_dependency_beacons();
    }

    protected function t_common_import_get_filters_for_database() {
        return [
            'id' => ['IntMore0'],
            'alias' => ['Strip', 'Trim', 'NEString'],
            'editor' => ['Boolean', 'DefaultTrue'],
            'comment' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'text' => ['Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

    protected function t_common_import_get_filters_for_post() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'alias' => ['Strip', 'Trim', 'NEString'],
            'editor' => ['Boolean', 'DefaultTrue'],
            'comment' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'text' => ['Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

    protected function t_common_import_get_filters_params_for_post() {
        return [];
    }

    protected function t_common_import_get_filters_params_for_database() {
        return [];
    }

    /**
     * 
     * @return \Content\ContentBlock\ContentBlock
     */
    public static function F(): ContentBlock {
        return new static();
    }

    /**
     * 
     * @param int $id
     * @return \Content\ContentBlock\ContentBlock
     */
    public static function LI(int $id): ContentBlock {
        $r = new static();
        $r->load_by_id($id);
        return $r;
    }

    /**
     * 
     * @param string $alias
     * @return \Content\ContentBlock\ContentBlock
     */
    public static function LA(string $alias): ContentBlock {
        $r = new static();
        $r->load($alias);
        return $r;
    }

    /**
     * 
     * @param string $alias
     * @return \Content\ContentBlock\ContentBlock
     */
    public static function C(string $alias): ContentBlock {
        $cache = \Cache\FileCache::F();
        $result = $cache->get(static::cache_id($alias)); /* @var $result static */
        $cm = static::class;
        if ($result && is_object($result) && ($result instanceof $cm) && $result->version === static::get_file_ver()) {
            return $result;
        } else {
            $result = static::LA($alias);
            if (!$result->empty) {
                $cache->put(static::cache_id($result->alias), $result, 0, \Cache\FileBeaconDependency::F(static::CACHE_DEP_BEACON));
            } else {
                $result->fill_default_content($alias);
            }
        }
        return $result;
    }

    public function get_text_for_render() {
        return sprintf("<!-- start content_block `%s` -->%s<!-- end of content_block `%s` -->", $this->alias, $this->text, $this->alias);
    }

    public function render(\Smarty $smarty = null, string $template = 'default', bool $return = false) {
        $smarty = $smarty ? $smarty : \smarty\SMW::F()->smarty;
        if ($return) {
            return $smarty->fetch(sprintf("string:%s", $this->get_text_for_render()));
        } else {
            $smarty->display(sprintf("string:%s", $this->get_text_for_render()));
        }
    }

    protected function fill_default_content(string $alias) {
        $this->empty = true;
        $this->text = sprintf("<!-- no content block `%s` -->", $alias);
    }

}
