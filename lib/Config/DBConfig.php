<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Config;

/**
 * 
 * @property string $server
 * @property int $port
 * @property string $db_name
 * @property string $user_name
 * @property string $password
 * @property string $instance_name
 * @property string $dsn
 */
final class DBConfig {

    use \common_accessors\TCommonAccess;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $server;

    /** @var int */
    protected $port;

    /** @var string */
    protected $db_name;

    /** @var string */
    protected $user_name;

    /** @var string */
    protected $password;

    /** @var string */
    protected $instance_name;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__server() {
        return $this->server;
    }

    /** @return int */
    protected function __get__port() {
        return $this->port;
    }

    /** @return string */
    protected function __get__db_name() {
        return $this->db_name;
    }

    /** @return string */
    protected function __get__user_name() {
        return $this->user_name;
    }

    /** @return string */
    protected function __get__password() {
        return $this->password;
    }

    /** @return string */
    protected function __get__instance_name() {
        return $this->instance_name;
    }
    
    protected function __get__dsn(){
        return "mysql:host={$this->server};port={$this->port};dbname={$this->db_name};charset=utf8";
    }

    //</editor-fold>

    protected function __construct(array $data) {
        $this->server = array_key_exists('server', $data) ? $data['server'] : '127.0.0.1';
        $this->port = array_key_exists('port', $data) ? $data['port'] : 3306;
        $this->db_name = array_key_exists('db', $data) ? $data['db'] : null;
        $this->user_name = array_key_exists('user', $data) ? $data['user'] : null;
        $this->password = array_key_exists('password', $data) ? $data['password'] : null;
        $this->instance_name = array_key_exists('id', $data) ? $data['id'] : "default";
    }

    public function is_valid(): bool {
        return $this->server && $this->port && $this->db_name && $this->instance_name ? true : false;
    }

    /**
     * 
     * @param array $data
     * @return \Config\DBConfig
     */
    public static function F(array $data): DBConfig {
        return new static($data);
    }

}
