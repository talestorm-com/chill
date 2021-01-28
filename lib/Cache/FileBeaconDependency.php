<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cache;

class FileBeaconDependency implements ICacheDependency {

    /** @var string[] */
    protected $beacons;

    /** @var string */
    protected $value;

    protected function __construct($beacons) {
        $this->beacons = [];
        if (!is_array($beacons) && is_string($beacons)) {
            $beacons = explode(",", $beacons);
        }
        if (is_array($beacons)) {
            foreach ($beacons as $beacon_string) {
                $beacon = \Helpers\Helpers::NEString($beacon_string, null);
                if ($beacon) {
                    $this->beacons[] = $beacon;
                }
            }
        }
        $this->create_all_beacons();
    }

    protected static function get_beacons_path() {
        $path = \Config\Config::F()->BASE_DIR . "_cache" . DIRECTORY_SEPARATOR . "_beacons" . DIRECTORY_SEPARATOR;
        if (!(file_exists($path) && is_dir($path))) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    protected function create_all_beacons() {
        $path = static::get_beacons_path();
        foreach ($this->beacons as $beacon) {
            $beacon_path = "{$path}{$beacon}.bic";
            if (!(file_exists($beacon_path) && is_file($beacon_path))) {
                file_put_contents($beacon_path, time());
            }
        }
        return $this;
    }

    public function get_dependency_current_value(): string {
        $values = [];
        $path = static::get_beacons_path();
        foreach ($this->beacons as $beacon) {
            $beacon_path = "{$path}{$beacon}.bic";
            if (file_exists($beacon_path) && is_file($beacon_path)) {
                $values[] = filemtime($beacon_path);
            } else {
                $values[] = 0;
            }
        }
        uasort($values, function($a, $b) {
            return $a - $b;
        });
        return md5(__CLASS__ . implode("", $values));
    }

    public function get_stored_dependency_value(): string {
        return $this->value;
    }

    public function store_dependency_value(): ICacheDependency {
        $this->value = $this->get_dependency_current_value();
        return $this;
    }

    /**
     * 
     * @param string|string[] $beacons
     * @return \Cache\FileBeaconDependency
     */
    public static function F($beacons): FileBeaconDependency {
        return new static($beacons);
    }

    public function is_valid(): bool {
        return $this->value === $this->get_dependency_current_value();
    }

    /**
     * update dependency beacon files times (resets all caches with this dependency)
     */
    public function reset_dependency_beacons() {
        $path = static::get_beacons_path();
        foreach ($this->beacons as $beacon) {
            $beacon_path = "{$path}{$beacon}.bic";
            if (file_exists($beacon_path) && is_file($beacon_path)) {
                touch($beacon_path);
            } else {
                file_put_contents($beacon_path, time());
            }
        }
    }

}
