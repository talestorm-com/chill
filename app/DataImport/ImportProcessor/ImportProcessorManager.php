<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\ImportProcessor;

class ImportProcessorManager implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    /** @var ImportProcesorInfo[] */
    protected $items;

    /** @var ImportProcessorInfo[] */
    protected $index;

    /** @var ImportProcessorManager */
    protected static $instance;

    protected function __construct() {
        $this->items = [];
        static::$instance = $this;
        $this->load();
    }

    protected function load() {
        $ns = "\\" . trim(__NAMESPACE__, "\\") . "\\Processor\\";
        $path = __DIR__ . DIRECTORY_SEPARATOR . "Processor" . DIRECTORY_SEPARATOR;
        $list = file_exists($path) && is_dir($path) && is_readable($path) ? scandir($path) : [];
        
        foreach ($list as $file_name) {
            $file_path = "{$path}{$file_name}";
            $m = [];
            if (file_exists($file_path) && is_file($file_path) && is_readable($file_path) && preg_match("/^(?P<n>(?P<pn>[^\.]{1,})ImportProcessor)\.php$/i", $file_name, $m)) {
                $class_name = $ns . $m['n'];
                
                $ipi = ImportProcessorInfo::F($class_name, $m['pn'], $file_path);
                if ($ipi->valid) {
                    $this->items[] = $ipi;
                    $this->index[$ipi->name] = $ipi;
                }
            }
        }
    }

    /**
     * 
     * @param string $name
     * @param mixed $default
     * @return \DataImport\ImportProcessor\ImportProcessorInfo
     */
    public function get_by_name(string $name, $default = null) {
        return array_key_exists($name, $this->index) ? $this->index[$name] : $default;
    }

    /**
     * 
     * @return \DataImport\ImportProcessor\ImportProcessorManager
     */
    public static function F(): ImportProcessorManager {
        return static::$instance ? static::$instance : new static();
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

}
