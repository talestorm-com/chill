<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cache;

class FileCache {

    const DEFAULT_LIFETIME = 60 * 60 * 24 * 365;

    protected $path;

    protected function __construct() {
        $this->path = static::get_cache_path();
    }

    /**
     * put value into chache
     * @param string $key  
     * @param mixed $data
     * @param int $lifetime
     * @param \Cache\ICacheDependency $dependency
     * @return $this
     */
    public function put(string $key, $data, int $lifetime = 0, ICacheDependency $dependency = null): FileCache {
        $lifetime = $lifetime ? $lifetime : static::DEFAULT_LIFETIME;
        $data_to_store = [
            'data' => $data,
            'dependency' => $dependency
        ];
        $key_to_store = $this->cache_key_to_file_name($key);
        $dependency ? $dependency->store_dependency_value() : false;
        $serialised = serialize($data_to_store);
        file_put_contents("{$this->path}{$key_to_store}", $serialised, LOCK_EX);
        touch("{$this->path}{$key_to_store}", time() + $lifetime);
        return $this;
    }

    protected function cache_key_to_file_name(string $key): string {
        return md5(implode("|", [__CLASS__, $key])) . ".bin";
    }

    public function get(string $key) {
        $file_name = $this->cache_key_to_file_name($key);
        $path = "{$this->path}{$file_name}";
        if (file_exists($path) && is_file($path) && filemtime($path) > time()) {
            $c = unserialize(file_get_contents($path));
            if (is_array($c) && array_key_exists('data', $c) && array_key_exists('dependency', $c)) {
                if ($c['dependency'] && ($c['dependency'] instanceof ICacheDependency)) {
                    if ($c['dependency']->is_valid()) {
                        return $c['data'];
                    }
                } else if ($c['dependency'] === null) {
                    return $c['data'];
                }
            }
        }
        return false;
    }

    public function remove(string $key): FileCache {
        $file_name = $this->cache_key_to_file_name($key);
        $path = "{$this->path}{$file_name}";
        if (file_exists($path) && is_file($path)) {
            unlink($path);
        }
        return $this;
    }

    public function purge(): FileCache {
        $files = scandir($this->path);
        foreach ($files as $file) {
            if (preg_match("/^[^.].*\.bin/i", $file)) {
                $path_to = "{$this->path}{$file}";
                if (file_exists($path_to) && is_file($path_to)) {
                    unlink($path_to);
                }
            }
        }
        return $this;
    }

    protected static function get_cache_path(): string {
        $path = \Config\Config::F()->BASE_DIR . "_cache" . DIRECTORY_SEPARATOR;
        if (!(file_exists($path) && is_dir($path))) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    public static function remove_expired() {
        $path = static::get_cache_path();
        $files = scandir($path);
        $now = time();
        foreach ($files as $file) {
            if (file_exists("{$path}{$file}") && is_file("{$path}{$file}") && filemtime("{$path}{$file}") < $now) {
                if (preg_match("/^[^\.].*\.bin/", $file)) {
                    @unlink($file);
                }
            }
        }
    }

    public static function S_PURGE() {
        static::F()->purge();
    }

    protected static function run_clean() {
        $x = mt_rand(0, 15000);
        if (($x % 5) === 0) {
            static::remove_expired();
        }
    }

    /**
     * 
     * @return \Cache\FileCache
     */
    public static function F(): FileCache {
        static::run_clean();
        return new static();
    }

}
