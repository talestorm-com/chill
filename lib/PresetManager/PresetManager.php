<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PresetManager;

/**
 * @property \DataMap\IDataMap $presets
 * @property string $version
 */
final class PresetManager implements \DataMap\IDataMap, IPresetsKeys, \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \DataMap\TInternalDataMapProxy;

    const CACHE_DEP_NAME = "PRESET_MANAGER";
    const MAP_CONST_BEGIN = "/** ---CONST BEGINS--- */";

    /** @var PresetManager */
    protected static $instance;

    /** @var \DataMap\IDataMap */
    protected $presets;

    /** @var string */
    protected $version;

    /** @var IChange[] */
    protected $changes;

    protected function t_array_data_map_get_internal_map() {
        return $this->presets;
    }

    public function rebind(array &$source): \DataMap\IDataMap {
        \DataMap\DataMapError::RF("`%s` is non rebindable", __CLASS__);
        return $this->t_array_data_map_get_internal_map()->rebind($source);
    }

    public function remove(string $key): \DataMap\IDataMap {
        $result = $this->t_array_data_map_get_internal_map()->remove($key);
        $this->add_change(new RemoveChange($key));
        return $result;
    }

    public function set(string $key, $value): \DataMap\IDataMap {
        if (!(is_string($value) || $value === null)) {
            \DataMap\DataMapError::RF("`%s` only accepts strings", __CLASS__);
        }//change
        $result = $this->t_array_data_map_get_internal_map()->set($key, $value);
        $this->add_change(new SetChange($key, $value));
        return $result;
    }

    protected function add_change(IChange $x) {
        is_array($this->changes) ? 0 : $this->changes = [];
        $this->changes[] = $x;
    }

    private static function get_file_ver(): string {
        return md5(implode("", [__FILE__, filemtime(__FILE__)]));
    }

    private static function get_cache_key(): string {
        return md5(__METHOD__);
    }

    private function __construct() {
        static::$instance = $this;
        $this->version = static::get_file_ver();
        $this->presets = \DataMap\CommonDataMap::F();
        $this->load();
        $this->set_cache();
        $this->update_consts();
    }

    private function update_consts() {
        $rows = [static::MAP_CONST_BEGIN];
        foreach ($this->presets->get_all_cloned() as $key => $value) {
            $rows[] = "const " . mb_strtoupper($key, 'UTF-8') . " = \"{$key}\";";
        }
        $replacer = implode("\n", $rows);
        $path = __DIR__ . DIRECTORY_SEPARATOR . "IPresetsKeys.php";
        $tplpath = __DIR__ . DIRECTORY_SEPARATOR . "IPresetsKeys_template_raw.php";
        $content = file_get_contents($tplpath);
        //$regex = "/" . preg_quote(static::MAP_CONST_BEGIN, "/") ."/im";
        //$new_content = preg_replace($regex, $replacer, $content);
        $new_content = str_ireplace(static::MAP_CONST_BEGIN, $replacer, $content);
        file_put_contents($path, $new_content, LOCK_EX);
    }

    private function load() {
        $source = [];
        $rows = \DB\DB::F()->queryAll("SELECT name,value FROM presets ORDER BY name;", []);
        foreach ($rows as $row) {
            $source[$row['name']] = $row["value"];
        }
        $this->presets->rebind($source);
    }

    private function set_cache() {
        $cache = \Cache\FileCache::F();
        $key = static::get_cache_key();
        $cache->put($key, $this, 0, \Cache\FileBeaconDependency::F(static::CACHE_DEP_NAME));
    }

    /**
     * 
     * @return \PresetManager\PresetManager
     */
    public static function F(): PresetManager {
        return static::$instance ? static::$instance : static::factory();
    }

    /**
     * 
     * @return \PresetManager\PresetManager
     */
    private static function factory(): PresetManager {
        $key = static::get_cache_key();
        $some = \Cache\FileCache::F()->get($key); /* @var $some static */
        $cs = static::class;
        if ($some && is_object($some) && ($some instanceof $cs) && $some->version === static::get_file_ver()) {
            static::$instance = $some;
            return $some;
        }
        return new static();
    }

    public static function RESET_CACHE() {
        \Cache\FileBeaconDependency::F(static::CACHE_DEP_NAME)->reset_dependency_beacons();
    }

    public function __sleep() {
        return ["presets", "version"];
    }

    public function flush() {
        if (is_array($this->changes) && count($this->changes)) {
            $b = \DB\SQLTools\SQLBuilder::F();
            foreach ($this->changes as $change_item) {
                $change_item->sql($b);
            }
            if (!$b->empty) {
                $b->execute_transact();
                \DB\errors\MySQLWarn::F($b->adapter);
            }
            $this->changes = null;
            static::RESET_CACHE();
        }
    }

    public function __destruct() {
        $this->flush();
    }

    public function marshall() {
        $result = [];
        foreach ($this->presets->get_all_cloned() as $key => $value) {
            $result[] = ["key" => $key, "value" => $value];
        }
        return $result;
    }

    public static function release_singleton() {
        static::$instance = null;
    }

}
