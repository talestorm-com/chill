<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GEOList;

/**
 * Description of GEOList
 *
 * @author eve
 */
class GEOList {

    protected static $instance;
    protected $enabled = false;
    protected $blacklist = false;
    protected $countries = null;
    protected $ignore_agents = null;

    protected function __construct() {
        static::$instance = $this;
        $this->load();
    }

    protected function load() {
        $this->countries = null;
        $v = \Config\Config::F()->GEOIP_LIST;
        $map = \DataMap\CommonDataMap::F()->rebind($v);
        $this->enabled = $map->get_filtered('enabled', ['Boolean', 'DefaultFalse']);
        $this->blacklist = $map->get_filtered('blacklist', ['Boolean', 'DefaultFalse']);
        $this->countries = $map->get_filtered('codes', ['NEArray', 'DefaultEmptyArray']);
        $this->ignore_agents = $map->get_filtered('no_check_agents', ['NEArray', 'DefaultEmptyArray']);
    }

    /**
     * 
     * @return \GEOList\GEOList
     */
    public static function F(): GEOList {
        return static::$instance ? static::$instance : new static();
    }

    protected function ua_no_check() {
        if (isset($_SERVER) && is_array($_SERVER) && array_key_exists('HTTP_USER_AGENT', $_SERVER) && is_string($_SERVER['HTTP_USER_AGENT'])) {
            if (count($this->ignore_agents)) {
                foreach ($this->ignore_agents as $regexp) {
                    try {
                        if (preg_match($regexp, $_SERVER['HTTP_USER_AGENT'])) {
                            return true;
                        }
                    } catch (\Throwable $e) {
                        
                    }
                }
            }
        }
        return false;
    }

    /**
     * 
     * @param string $country_code
     * @return bool
     */
    public function has_access(string $country_code): bool {
        if (!$this->enabled) {
            return true;
        }
        if ($this->ua_no_check()) {
            return true;
        }
        if ($this->blacklist) {
            return !array_key_exists($country_code, $this->countries);
        } else {
            return array_key_exists($country_code, $this->countries);
        }
    }

    /**
     * 
     * @param string $ip_address
     * @return bool
     */
    public function has_access_from_host(string $ip_address): bool {
	if(!$this-enabled){
		return true;
	}
        $country_code = geoip_country_code3_by_name($ip_address);
        if ($country_code) {
            return $this->has_access($country_code);
        }
        return false;
    }

    /**
     * 
     * @param string $ip_header
     * @return bool
     */
    public function has_access_client(string $ip_header = 'HTTP_X_REAL_IP'): bool {
        if(!$this->enabled){
	 return true;
	}
        return $this->has_access_from_host($_SERVER[$ip_header]);
    }

    public function disable() {
        $this->enabled = false;
    }

}
