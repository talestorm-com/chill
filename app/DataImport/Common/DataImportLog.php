<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\Common;

class DataImportLog implements \common_accessors\IMarshall {

    use \common_accessors\TDefaultMarshaller;

    protected static $instance = null;
    protected $items = [];

    protected function __construct() {
        static::$instance = $this;
        $this->items = [];
    }

    public function clear() {
        $this->items = [];
        return $this;
    }

    public function log(string $message): DataImportLog {
        $this->items[] = $message;
        return $this;
    }

    /**
     * 
     * @return \DataImport\Common\DataImportLog
     */
    public static function F(): DataImportLog {
        return static::$instance ? static::$instance : new static();
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    public function get_text(string $separator = "\n"): string {
        return implode($separator, $this->items);
    }
    
    public static function destroy_instance(){
        static::$instance = null;
    }

}
