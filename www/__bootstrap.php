<?php

namespace a35115dc61264b38be64e25bb0aeb65e;

final class Bootstrap {

    /** @var Bootstrap */
    private static $instance = null;

    private function __construct() {
        require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "loader.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.php";
        static::$instance = $this;
    }

    public static function F(): Bootstrap {
        return static::$instance ? static::$instance : new static();
    }

    public function get_bootstrap_path(): string {
        return __FILE__;
    }

    public function get_bootstrap_dir(): string {
        return __DIR__;
    }

}

Bootstrap::F();
