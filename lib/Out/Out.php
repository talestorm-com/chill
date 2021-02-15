<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out;

/**
 * @property assets\AssetManager $assets
 * @property Metadata\MetadataManager $meta
 */
class Out implements IOut {

    use \common_accessors\TCommonAccess,
        assets\TAssetManagerDelegate,
        \common_accessors\TDefaultMarshaller;

    /** @var Out */
    private static $instance;
    private $data = [
        'default' => []
    ];
    private $couter = 0;

    /** @var assets\AssetManager */
    private $assets;

    /** @var Metadata\MetadataManager */
    private $meta;

    protected function __get__assets() {
        return $this->assets;
    }

    protected function __get__meta() {
        return $this->meta;
    }

    private function __construct() {
        static::$instance = $this;
        $this->assets = assets\DefaultAssetManager::F();
        $this->meta = Metadata\MetadataManager::F();
    }

    /**
     * 
     * @return \static
     */
    public static function F(): IOut {
        return static::$instance ? static::$instance : new static();
    }

    public function add(string $key, $data, string $section = 'default'): IOut {
        if (!array_key_exists($section, $this->data)) {
            $this->data[$section] = [];
        }
        $this->data[$section][$key] = $data;
        return $this;
    }

    public function get(string $key, string $section = 'default') {
        if (array_key_exists($section, $this->data)) {
            if (array_key_exists($key, $this->data[$section])) {
                return $this->data[$section][$key];
            }
        }
        return null;
    }

    public function getOpt(string $key, $default = null, $section = 'default') {
        if (array_key_exists($section, $this->data)) {
            if (array_key_exists($key, $this->data[$section])) {
                return $this->data[$section][$key];
            }
        }
        return $default;
    }

    public function remove(string $key, string $section = 'default'): IOut {
        if (array_key_exists($section, $this->data)) {
            if (is_array($this->data[$section])) {
                if (array_key_exists($key, $this->data[$section])) {
                    unset($this->data[$section][$key]);
                    if (!count($this->data[$section])) {
                        $this->remove_section($section);
                    }
                }
            }
        }
        return $this;
    }

    public function remove_section(string $section): IOut {
        if (array_key_exists($section, $this->data)) {
            unset($this->data[$section]);
        }
        return $this;
    }

    public function replace_section(string $section, array $value): IOut {
        $this->data[$section] = $value;
        return $this;
    }

    protected function t_asset_manager_delegate_get_internal_object(): assets\IAssetManager {
        return $this->assets;
    }

    protected function prepare_json_output(): array {
        $result = [];
        foreach ($this->data as $section_key => $section_data) {
            if ($section_key === 'default') {
                $result = array_merge($result, $section_data);
            } else {
                $result[$section_key] = $section_data;
            }
        }
        return $result;
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->prepare_json_output());
    }

    public function get_euid(string $x = null): string {
        $this->couter++;
        $prefix = \Helpers\Helpers::NEString($x, "uid");
        return "{$prefix}_{$this->couter}";
    }

    public function get_uuid() {
        return "block" . str_ireplace("-", '', \Helpers\Helpers::guid_v4());
    }

}
