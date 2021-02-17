<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Infopage;

/**
 * @property int $id 
 * @property string $guid
 * @property string $alias 
 * @property string $title 
 * @property bool $published 
 * @property int $dop 
 * @property string $version
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $intro
 * @property string $content
 * @property bool $html_mode_c
 * @property bool $html_mode_i
 * @property string $rendered_content
 * @property string $rendered_intro
 * @property \Content\IImageCollection $images
 * @property \Content\Infopage\InfopageProps $properties
 */
class Infopage extends \Content\Content implements \Content\IMetaSupport, \Content\IImageSupport {

    use \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="const">
    const CACHE_DEP_BEACON = "infopage";
    const LOAD_MODE_DB = "database";
    const LOAD_MODE_POST = "post";

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $guid;

    /** @var string */
    protected $alias;

    /** @var string */
    protected $title;

    /** @var bool */
    protected $published;

    /** @var bool */
    protected $html_mode_c;

    /** @var bool */
    protected $html_mode_i;

    /** @var int */
    protected $dop;

    /** @var string */
    protected $version;

    /** @var string */
    protected $meta_title;

    /** @var string */
    protected $meta_keywords;

    /** @var string */
    protected $meta_description;

    /** @var string */
    protected $intro;

    /** @var string */
    protected $content;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var \Content\Infopage\InfopageProps */
    protected $properties;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__guid() {
        return $this->guid;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    /** @return string */
    protected function __get__title() {
        return $this->title;
    }

    /** @return bool */
    protected function __get__published() {
        return $this->published;
    }

    /** @return bool */
    protected function __get__html_mode_c() {
        return $this->html_mode_c;
    }

    /** @return bool */
    protected function __get__html_mode_i() {
        return $this->html_mode_i;
    }

    /** @return int */
    protected function __get__dop() {
        return $this->dop;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    /** @return string */
    protected function __get__meta_title() {
        return $this->meta_title;
    }

    /** @return string */
    protected function __get__meta_keywords() {
        return $this->meta_keywords;
    }

    /** @return string */
    protected function __get__meta_description() {
        return $this->meta_description;
    }

    /** @return string */
    protected function __get__intro() {
        return $this->intro;
    }

    /** @return string */
    protected function __get__content() {
        return $this->content;
    }

    protected function __get__rendered_content() {
        $smarty = \smarty\SMW::F()->smarty;
        $bak = $smarty->getTemplateVars('gallery_holder');
        $smarty->assign('gallery_holder', $this);
        $result = $smarty->fetch("string:{$this->content}");
        $smarty->assign('gallery_holder', $bak);
        return $result;
    }

    protected function __get__rendered_intro() {
        $smarty = \smarty\SMW::F()->smarty;
        $bak = $smarty->getTemplateVars('gallery_holder');
        $smarty->assign('gallery_holder', $this);
        $result = $smarty->fetch("string:{$this->intro}");
        $smarty->assign('gallery_holder', $bak);
        return $result;
    }

    protected function __get__images() {
        return $this->images;
    }

    protected function __get__properties() {
        return $this->properties;
    }

    //</editor-fold>


    public function render_content() {
        $smarty = \smarty\SMW::F()->smarty;
        $bak = $smarty->getTemplateVars('gallery_holder');
        $smarty->assign('gallery_holder', $this);
        $smarty->display("string:{$this->content}");
        $smarty->assign('gallery_holder', $bak);
    }

    public function render_intro() {
        $smarty = \smarty\SMW::F()->smarty;
        $bak = $smarty->getTemplateVars('gallery_holder');
        $smarty->assign('gallery_holder', $this);
        $smarty->display("string:{$this->intro}");
        $smarty->assign('gallery_holder', $bak);
    }

    protected function __construct() {
        $this->version = static::get_file_ver();
        $this->properties = \Content\Infopage\InfopageProps::F();
    }

    protected static function get_file_ver() {
        return md5(implode(":", [__CLASS__, filemtime(__FILE__)]));
    }

    protected static function cache_id(string $alias): string {
        return implode(":", [__CLASS__, $alias]);
    }

    public function load(string $alias, \DB\IDBAdapter $adapter = null) {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $row = $adapter->queryRow("SELECT * FROM infopage WHERE alias=:Palias;", [":Palias" => $alias]);
        $row ? 0 : \Router\NotFoundError::R("not found");
        $row ? false : \Errors\common_error::R("not found");
        $this->import_props($row, null, static::LOAD_MODE_DB);
        $this->properties->load_from_database($this->id, $adapter);
    }

    public function load_by_id(int $id, \DB\IDBAdapter $adapter = null) {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $row = $adapter->queryRow("SELECT *, UNIX_TIMESTAMP(dop) dop FROM infopage WHERE id=:Pid;", [":Pid" => $id]);
        $row ? false : \Errors\common_error::R("not found");
        $this->import_props($row, null, static::LOAD_MODE_DB);
        $this->properties->load_from_database($this->id, $adapter);
    }

    public function load_from_datamap(\DataMap\IDataMap $map) {
        $this->import_props_datamap($map, null, static::LOAD_MODE_POST);
        $this->properties->load_from_object_array($map->get_filtered('properties', ["NEArray", "DefaultEmptyArray"]));
    }

    /**
     * 
     * @param \DB\IDBAdapter $adapter
     * @return int  id of saved block
     */
    public function save(\DB\IDBAdapter $adapter = null): int {
        $this->alias ? 0 : $this->alias = \Helpers\Helpers::translit($this->title);
        $this->alias = \Helpers\Helpers::uniqueAlias('infopage', $this->alias, $this->id, $adapter);
        $b = \DB\SQLTools\SQLBuilder::F($adapter);
        $tn = "@a" . md5(__METHOD__);
        if ($this->id) {
            $b->push("SET {$tn} = :P{$b->c}id;");
            $b->push_param(":P{$b->c}id", $this->id);
            $b->push("UPDATE infopage SET alias=:P{$b->c}alias,title=:P{$b->c}title,
                published=:P{$b->c}published,meta_title=:P{$b->c}meta_title,
                meta_keywords=:P{$b->c}meta_keywords,meta_description=:P{$b->c}meta_description,
                intro=:P{$b->c}intro,content=:P{$b->c}content,
                html_mode_c=:P{$b->c}html_mode_c,
                html_mode_i=:P{$b->c}html_mode_i
             WHERE id={$tn};");
        } else {
            $b->push("INSERT into infopage (guid,alias,published,title,meta_title,intro,content,meta_keywords,meta_description,html_mode_c,html_mode_i)
                VALUES(UUID(),:P{$b->c}alias,:P{$b->c}published,:P{$b->c}title,:P{$b->c}meta_title,:P{$b->c}intro,:P{$b->c}content,:P{$b->c}meta_keywords,
                    :P{$b->c}meta_description,:P{$b->c}html_mode_c,:P{$b->c}html_mode_i);");
            $b->push("SET {$tn} = LAST_INSERT_ID();");
        }
        $b->push_params([
            ":P{$b->c}alias" => $this->alias,
            ":P{$b->c}title" => $this->title,
            ":P{$b->c}published" => $this->published ? 1 : 0,
            ":P{$b->c}meta_title" => $this->meta_title,
            ":P{$b->c}meta_keywords" => $this->meta_keywords,
            ":P{$b->c}meta_description" => $this->meta_description,
            ":P{$b->c}intro" => $this->intro,
            ":P{$b->c}content" => $this->content,
            ":P{$b->c}html_mode_c" => $this->html_mode_c ? 1 : 0,
            ":P{$b->c}html_mode_i" => $this->html_mode_i ? 1 : 0,
        ]);

        $this->properties->save($b, $tn);
        $ret = $b->execute_transact($tn);
        static::RESET_CACHE();
        return $ret;
    }

    public static function RESET_CACHE() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEP_BEACON)->reset_dependency_beacons();
    }

    protected function t_common_import_get_filters_for_database() {
        return [
            "id" => ['IntMore0'], //int
            "guid" => ['Strip', 'Trim', 'NEString'], //string
            "alias" => ['Strip', 'Trim', 'NEString'], //string
            "title" => ['Strip', 'Trim', 'NEString'], //string
            "published" => ['Boolean', 'DefaultTrue'], //bool
            "dop" => ['IntMore0', 'DefaultNull'], //int
            "meta_title" => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            "meta_keywords" => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            "meta_description" => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            "intro" => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            "content" => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            "html_mode_c" => ['Boolean', 'DefaultTrue'], //bool
            "html_mode_i" => ['Boolean', 'DefaultTrue'], //bool
        ];
    }

    protected function t_common_import_get_filters_for_post() {
        return [
            "id" => ['IntMore0', 'DefaultNull'], //int
            "alias" => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            "title" => ['Strip', 'Trim', 'NEString'], //string
            "published" => ['Boolean', 'DefaultTrue'], //bool
            "meta_title" => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            "meta_keywords" => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            "meta_description" => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            "intro" => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            "content" => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            "html_mode_c" => ['Boolean', 'DefaultTrue'], //bool
            "html_mode_i" => ['Boolean', 'DefaultTrue'], //bool            
        ];
    }

    protected function t_common_import_get_filters_params_for_post() {
        return [];
    }

    protected function t_common_import_get_filters_params_for_database() {
        return [];
    }

    protected function t_common_import_after_import() {
        parent::t_common_import_after_import();
        $this->images = \Content\DefaultImageCollection::F("infopage_gallery", (string) $this->id);
    }

    /**
     *
     * 
     * @return \Infopage\Infopage
     */
    public static function F(): Infopage {
        return new static();
    }

    /**
     * @param int $id
     * @return \Content\Infopage\Infopage
     */
    public static function LI(int $id): Infopage {
        $r = new static();
        $r->load_by_id($id);
        return $r;
    }

    /**

     * 
     * @param string $alias
     * @return \Content\Infopage\Infopage
     */
    public static function LA(string $alias): Infopage {
        $r = new static();
        $r->load($alias);
        return $r;
    }

    /**
     * 
     * @param string $alias
     * @return \Content\Infopage\Infopage
     */
    public static function C(string $alias): Infopage {
        $cache = \Cache\FileCache::F();
        $result = $cache->get(static::cache_id($alias)); /* @var $result static */
        $cm = static::class;
        if ($result && is_object($result) && ($result instanceof $cm) && $result->version === static::get_file_ver()) {
            return $result;
        } else {
            $result = static::LA($alias);
            $cache->put(static::cache_id($result->alias), $result, 0, \Cache\FileBeaconDependency::F(static::CACHE_DEP_BEACON));
        }
        return $result;
    }

    public function get_has_images(): bool {
        return $this->images->get_has_images();
    }

    public function get_images_count(): int {
        return $this->images->get_images_count();
    }

    public function get_object_images(): \Content\IImageCollection {
        return $this->images;
    }

    public function get_page_keywords(): string {
        return \Helpers\Helpers::NEString($this->meta_keywords, '');
    }

    public function get_page_meta_description(): string {
        return \Helpers\Helpers::NEString($this->meta_description, '');
    }

    public function get_page_meta_title(): string {
        return \Helpers\Helpers::NEString($this->meta_title, \Helpers\Helpers::NEString($this->title, ''));
    }

    public function get_page_title(): string {
        return \Helpers\Helpers::NEString($this->title, \Helpers\Helpers::NEString($this->meta_title, ''));
    }

}
