<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AgeRestriction;

/**
 * Description of AgeRestriction
 *
 * @author eve
 * @property int $id
 * @property string $international_name
 * @property string[] $name
 * @property string $default_image
 * @property bool $valid
 */
class AgeRestriction implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    const MEDIA_CONTEXT = "age_restriction";
    const CACHE_DEPENDENCY = "AGE_RESTRICTION";

    private static $_file_version;

    public static function get_file_version() {
        if (!static::$_file_version) {
            static::$_file_version = md5(implode("-", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_file_version;
    }

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $international_name;

    /** @var string[] */
    protected $name;

    /** @var string */
    protected $default_image;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__international_name() {
        return $this->international_name;
    }

    /** @return string[] */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__default_image() {
        return $this->default_image;
    }

    /** @return bool */
    protected function __get__valid() {
        return !!($this->id && $this->international_name);
    }

    //</editor-fold>

    public function __construct(int $id = null) {
        $this->name = [];
        if ($id) {
            $this->load($id);
        }
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id = null) {
        return new static($id);
    }

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function load(int $id) {
        $row = \DB\DB::F()->queryRow("SELECT * FROM media__age__restriction WHERE id=:P", [":P" => $id]);
        $this->import_props(is_array($row) ? $row : []);
        return $this;
    }

    protected function t_common_import_get_filters(): array {
        return[
            'id' => ['IntMore0', 'DefaultNull'],
            'international_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    protected function t_common_import_after_import() {
        if ($this->id) {
            $rows = \DB\DB::F()->queryAll("SELECT * FROM media__age__restriction__strings WHERE id=:P", [":P" => $this->id]);
            $ni = [];
            foreach ($rows as $row) {
                $language_id = \Filters\FilterManager::F()->apply_chain($row["language_id"], ['Strip', 'Trim', 'NEString', 'DefaultNull']);
                $name = \Filters\FilterManager::F()->apply_chain($row["name"], ['Strip', 'Trim', 'NEString', 'DefaultNull']);
                if ($language_id && $name) {
                    $ni[$language_id] = $name;
                }
            }
            $this->name = $ni;
        }
    }

    public static function reset_cached() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEPENDENCY)->reset_dependency_beacons();
    }

    public static function get_all() {
        $query = sprintf("SELECT A.id,A.international_name,COALESCE(B.name,C.name) name,A.default_image           
            FROM media__age__restriction A
            LEFT JOIN media__age__restriction__strings B ON(A.id=B.id AND B.language_id='%s')
            LEFT JOIN media__age__restriction__strings C ON(A.id=C.id AND C.language_id='%s')            
            ",
                \Language\LanguageList::F()->get_current_language(),
                \Language\LanguageList::F()->get_default_language());
        return \DB\DB::F()->queryAll($query);
    }

}
