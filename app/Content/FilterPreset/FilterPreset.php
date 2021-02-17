<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\FilterPreset;

/**
 * Description of FilterPreset
 *
 * @author eve
 * @property integer $id
 * @property string $guid
 * @property string $name
 * @property string $alias
 * @property bool $active
 * @property bool $html_mode
 * @property \DateTime $published
 * @property double $cost
 * @property \DateTime $created
 * @property \DateTime $updated
 * @property string $default_image
 * @property string $info
 * @property PropertyCollection $properties
 * @property FilterPresetItem[] $items
 * @property \Content\IImageCollection $images
 * @property string $class_version
 * @property int $marshall_mode
 */
class FilterPreset extends \Content\Content implements \Content\IImageSupport {

    use \common_accessors\TCommonImport;

    CONST MEDIA_CONTEXT = 'filterpreset';
    CONST CACHE_BEAKON = 'filterpreset';
    CONST MARSHALL_MODE_FULL = 0;
    CONST MARSHALL_MODE_TRIM = 1;
    CONST ACCESS_KEY = "PST";

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var integer */
    protected $id;

    /** @var string */
    protected $guid;

    /** @var string */
    protected $name;

    /** @var string */
    protected $alias;

    /** @var bool */
    protected $active;

    /** @var bool */
    protected $html_mode;

    /** @var \DateTime */
    protected $published;

    /** @var double */
    protected $cost;

    /** @var \DateTime */
    protected $created;

    /** @var \DateTime */
    protected $updated;

    /** @var string */
    protected $default_image;

    /** @var string */
    protected $info;

    /** @var PropertyCollection */
    protected $properties;

    /** @var FilterPresetItem[] */
    protected $items;

    /** @var string */
    protected $class_version;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var int */
    protected $marshall_mode = self::MARSHALL_MODE_FULL;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return \Content\IImageCollection */
    protected function __get__images() {
        return $this->images;
    }

    /** @return integer */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__guid() {
        return $this->guid;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    /** @return bool */
    protected function __get__active() {
        return $this->active;
    }

    /** @return bool */
    protected function __get__html_mode() {
        return $this->html_mode;
    }

    /** @return \DateTime */
    protected function __get__published() {
        return $this->published;
    }

    /** @return double */
    protected function __get__cost() {
        return $this->cost;
    }

    /** @return \DateTime */
    protected function __get__created() {
        return $this->created;
    }

    /** @return \DateTime */
    protected function __get__updated() {
        return $this->updated;
    }

    /** @return string */
    protected function __get__default_image() {
        return $this->default_image;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return PropertyCollection */
    protected function __get__properties() {
        return $this->properties;
    }

    /** @return FilterPresetItem[] */
    protected function __get__items() {
        return $this->items;
    }

    /** @return string */
    protected function __get__class_version() {
        return $this->class_version;
    }

    /** @return int */
    protected function __get__marshall_mode() {
        return $this->marshall_mode;
    }

    protected function __set__marshall_mode(int $x) {
        $this->marshall_mode = $x;
        foreach ($this->items as $item) {
            $item->marshall_mode = $x;
        }
    }

    //</editor-fold>



    public function __wakeup() {
        $this->marshall_mode = static::MARSHALL_MODE_FULL;
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

    protected function t_default_marshaller_export_property_created() {
        return $this->created ? $this->created->format('d.m.Y H:i:s') : null;
    }

    protected function t_default_marshaller_export_property_updated() {
        return $this->updated ? $this->updated->format('d.m.Y H:i:s') : null;
    }

    protected function t_default_marshaller_export_property_published() {
        return $this->published ? $this->published->format('d.m.Y H:i:s') : null;
    }

    public static function get_class_ver(): string {
        return md5(implode(",", [__FILE__, filemtime(__FILE__), FilterPresetItem::get_class_ver()]));
    }

    /**
     * 
     * @return \Content\FilterPreset\FilterPreset
     */
    public static function F(): FilterPreset {
        return new static();
    }

    public function __construct() {
        $this->properties = FilterPresetPropertyCollection::F();
        $this->images = \Content\DefaultImageCollection::F();
        $this->items = [];
        $this->class_version = static::get_class_ver();
    }

    public function load(int $id): FilterPreset {
        $query = "SELECT * FROM filterpreset WHERE id=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
        return $this;
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //integer
            'guid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'alias' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'active' => ['Boolean', 'DefaultFalse'], //bool
            'html_mode' => ['Boolean', 'DefaultTrue'], //bool
            'published' => ['DateMatch', 'DefaultNull'], //\DateTime
            'cost' => ['Float', 'Default0'], //double
            'created' => ['DateMatch', 'DefaultNull'], //\DateTime
            'updated' => ['DateMatch', 'DefaultNull'], //\DateTime
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'info' => ['Trim', 'NEString', 'DefaultNull'], //string                                             
        ];
    }

    protected function t_common_import_after_import() {
        if ($this->id && $this->name && $this->created && $this->guid) {
            $this->images = \Content\DefaultImageCollection::F(static::MEDIA_CONTEXT, $this->id);
            $this->properties = FilterPresetPropertyCollection::F()->load_from_database($this->id);
            $this->load_items();
        }
    }

    protected function load_items() {
        $this->items = [];
        if ($this->id) {
            $query = "SELECT A.*,B.name package_name,B.cost package_cost FROM filterpreset__item A JOIN filterpreset B ON(A.id=B.id) WHERE A.id=:P ORDER BY A.sort,A.uid";
            $rows = \DB\DB::F()->queryAll($query, [":P" => $this->id]);
            foreach ($rows as $row) {
                $item = FilterPresetItem::F($row);
                $item && $item->valid ? $this->items[] = $item : 0;
            }
        }
    }

    public static function C(int $id): FilterPreset {
        $cache_key = implode("_", [static::class, $id]);
        $cached_value = \Cache\FileCache::F()->get($cache_key);
        if ($cached_value && ($cached_value instanceof FilterPreset) && $cached_value->class_version === static::get_class_ver()) {
            return $cached_value;
        }
        $value = static::F();
        $value->load($id);
        \Cache\FileCache::F()->put($cache_key, $value, 0, \Cache\FileBeaconDependency::F(static::CACHE_BEAKON));
        return $value;
    }

    protected function t_default_marshaller_on_props_to_marshall(array &$props) {
        if ($this->marshall_mode === static::MARSHALL_MODE_TRIM) {
            if (array_key_exists("marshall_mode", $props)) {
                unset($props["marshall_mode"]);
            }
            if (array_key_exists("html_mode", $props)) {
                unset($props["html_mode"]);
            }
        }
    }

}
