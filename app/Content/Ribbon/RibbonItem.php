<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Ribbon;

/**
 * Description of SerpentItem
 *
 * @author eve
 * 
 * @property int $id
 * @property string $target
 * @property bool $active
 * @property \DateTime $published
 * @property string $title
 * @property string $image
 * @property string $link_type
 * @property string $link_uid
 * @property int $link_id
 * @property string $intro
 * @property string $info
 * @property string $version
 * @property string $image_context 
 * @property int $image_owner_id
 * @property bool $html_mode
 * @property bool $html_mode_c
 * 
 */
class RibbonItem extends \Content\Content implements \common_accessors\IMarshall {

    use \common_accessors\TCommonImport;

    const MEDIA_CONTEXT = "ribbon_item";
    const CACHE_DEPENDENCY = "ribbon_item";

    /** @var string */
    protected static $_cv = null;
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $target;

    /** @var bool */
    protected $active;

    /** @var \DateTime */
    protected $published;

    /** @var string */
    protected $title;

    /** @var string */
    protected $image;

    /** @var string */
    protected $link_uid;

    /** @var int */
    protected $link_id;

    /** @var string */
    protected $intro;

    /** @var string */
    protected $info;

    /** @var string */
    protected $version;

    /** @var string */
    protected $link_type;

    /** @var string */
    protected $image_context;

    /** @var int */
    protected $image_owner_id;

    /** @var bool */
    protected $html_mode;

    /** @var bool */
    protected $html_mode_c;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__target() {
        return $this->target;
    }

    /** @return bool */
    protected function __get__active() {
        return $this->active;
    }

    /** @return \DateTime */
    protected function __get__published() {
        return $this->published;
    }

    /** @return string */
    protected function __get__title() {
        return $this->title;
    }

    /** @return string */
    protected function __get__image() {
        return $this->image;
    }

    /** @return string */
    protected function __get__link_uid() {
        return $this->link_uid;
    }

    /** @return int */
    protected function __get__link_id() {
        return $this->link_id;
    }

    /** @return string */
    protected function __get__intro() {
        return $this->intro;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return string */
    protected function __get__version() {
        return $this->version;
    }

    /** @return string */
    protected function __get__link_type() {
        return $this->link_type;
    }

    /** @return string */
    protected function __get__image_context() {
        return $this->image_context;
    }

    /** @return int */
    protected function __get__image_owner_id() {
        return $this->image_owner_id;
    }

    /** @return bool */
    protected function __get__html_mode() {
        return $this->html_mode;
    }

    /** @return bool */
    protected function __get__html_mode_c() {
        return $this->html_mode_c;
    }

    //</editor-fold>


    public static function get_file_ver() {
        if (!static::$_cv) {
            static::$_cv = md5(implode(".", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_cv;
    }

    public function __construct(int $id = null) {
        $this->version = static::get_file_ver();
        if ($id) {
            $this->load($id);
        }
    }

    /**
     * 
     * @param int $id
     * @return \Content\Ribbon\RibbonItem
     */
    public static function F(int $id = null): RibbonItem {
        return new static($id);
    }

    /**
     * 
     * @param int $id
     * @return \Content\Ribbon\RibbonItem
     */
    public function load(int $id): RibbonItem {

        $query = "SELECT A.*,
            COALESCE(A.image,PST.default_image,VTR.default_image,NULL) image,
            CASE 
                WHEN A.image IS NOT NULL THEN '" . static::MEDIA_CONTEXT . "' 
                WHEN PST.default_image IS NOT NULL THEN '" . \Content\FilterPreset\FilterPreset::MEDIA_CONTEXT . "'
                WHEN VTR.default_image IS NOT NULL THEN '" . \Content\Video\VideoGroup::MEDIA_CONTEXT . "' 
                ELSE NULL END 'image_context'   ,
            CASE WHEN A.image IS NOT NULL THEN A.id
                 WHEN PST.default_image IS NOT NULL THEN PST.id
                 WHEN VTR.default_image IS NOT NULL THEN VTR.id
                 ELSE NULL END image_owner_id            
            FROM ribbon A 
            LEFT JOIN video__group VTR ON(A.link_type='" . \Content\Video\VideoGroup::ACCESS_KEY . "' AND A.link_id=VTR.id)
            LEFT JOIN filterpreset PST ON(A.link_type='" . \Content\FilterPreset\FilterPreset::ACCESS_KEY . "' AND A.link_id=PST.id)
            WHERE A.id=:P";
        $data = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $data ? 0 : \Errors\common_error::R("not found");
        $this->import_props($data);
        return $this;
    }

    protected function t_default_marshaller_export_property_published() {
        return $this->published ? $this->published->format('d.m.Y') : null;
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //int
            'target' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'active' => ['Boolean', 'DefaultFalse'], //bool
            'published' => ['DateMatch', 'DefaultNull'], //\DateTime
            'title' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'link_uid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'link_id' => ['IntMore0', 'DefaultNull'], //int
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'link_type' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'image_context' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string            
            'image_owner_id' => ['IntMore0', 'DefaultNull'], //string            
            'html_mode' => ['Boolean', 'DefaultTrue'],
            'html_mode_c' => ['Boolean', 'DefaultTrue']
        ];
    }

    public static function create_message_link(string $title, string $key, int $link_id = null, string $link_uid = null, bool $keep = false, bool $draft = false) {
        if ($link_id || $link_uid) {
            $b = \DB\SQLTools\SQLBuilder::F();
            if (!$keep) {
                if ($link_id) {
                    $query = "DELETE FROM ribbon WHERE link_type=:P{$b->c}t AND link_id=:P{$b->c}i;";
                    $b->push_param(":P{$b->c}i", $link_id);
                } else {
                    $query = "DELETE FROM ribbon WHERE link_type=:P{$b->c}t AND link_uid=:P{$b->c}i;";
                    $b->push_param(":P{$b->c}i", $link_uid);
                }
                $b->push($query);
                $b->push_param(":P{$b->c}t", $key);
            }
            $b->inc_counter();
            $b->push("INSERT INTO ribbon(title,active,published,link_type,link_id,link_uid,intro,info) VALUES(:P{$b->c}title,:P{$b->c}active,:P{$b->c}published,
                :P{$b->c}link_type,:P{$b->c}link_id,:P{$b->c}link_uid,'',''
                )");
            $b->push_params([
                ":P{$b->c}title" => $title,
                ":P{$b->c}active" => $draft ? 0 : 1,
                ":P{$b->c}published" => null,
                ":P{$b->c}link_type" => $key,
                ":P{$b->c}link_id" => $link_id ? $link_id : null,
                ":P{$b->c}link_uid" => $link_id ? null : $link_uid,
            ]);
            $b->execute_transact();
            static::reset_cache();
        }
    }

    public static function reset_cache() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY)->reset_dependency_beacons();
    }

}
