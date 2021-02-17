<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\ImportProcessor;

/**
 * @property string $class
 * @property string $display_name
 * @property string $file
 * @property string $description
 * @property string $name
 * @property bool $valid
 */
class ImportProcessorInfo implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    /** @var string */
    protected $class;

    /** @var string */
    protected $display_name;

    /** @var string */
    protected $file;

    /** @var string */
    protected $description;

    /** @var string */
    protected $name;

    /** @return string */
    protected function __get__class() {
        return $this->class;
    }

    /** @return string */
    protected function __get__display_name() {
        return $this->display_name;
    }

    /** @return string */
    protected function __get__file() {
        return $this->file;
    }

    /** @return string */
    protected function __get__description() {
        return $this->description;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->class && $this->name && $this->file && file_exists($this->file) && is_file($this->file) && is_readable($this->file);
    }

    protected function t_default_marshaller_export_property_file() {
        return null;
    }

    /**
     * 
     * @param \DataImport\ImportProcessor\IIMportProcessor $class_name
     * @param string $processor_name
     * @param string $file_path
     */
    public function __construct(string $class_name, string $processor_name, string $file_path) {
        $class_name = \Helpers\Helpers::ref_classs_to_root($class_name);
        if (class_exists($class_name) && \Helpers\Helpers::class_implements($class_name, IImportProcessor::class)) {
            /* @var $class_name IIMportProcessor */
            $this->class = \Helpers\Helpers::ref_classs_to_root($class_name::get_class_name());
            $this->description = $class_name::get_processor_description();
            $this->display_name = $class_name::get_display_name();
            $this->name = \Filters\FilterManager::F()->apply_chain($processor_name, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $this->file = $file_path;
        }
    }

    /**
     * 
     * @return \DataImport\ImportProcessor\IImportProcessor
     */
    public function instance(): IImportProcessor {
        $class_name = $this->class;
        /* @var $class_name IIMportProcessor */
        return $class_name::instance();
    }

    /**
     * 
     * @param string $cs
     * @param string $processor_name
     * @param string $file_path
     * @return \DataImport\ImportProcessor\ImportProcessorInfo
     */
    public static function F(string $cs, string $processor_name, string $file_path): ImportProcessorInfo {
        return new static($cs,$processor_name,$file_path);
    }

}
