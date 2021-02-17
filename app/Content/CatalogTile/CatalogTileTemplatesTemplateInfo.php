<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile;

/**
 * @property string $file_name  xxx.tpl
 * @property string $file_path  /dir/dir2/xxx.tpl
 * @property string $name    xxx
 * @property string $info   text description
 * @property string $dir    /dir/dir <b>no end separator</b>
 * @property bool $valid
 */
class CatalogTileTemplatesTemplateInfo implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $file_name;

    /** @var string */
    protected $file_path;

    /** @var string */
    protected $name;

    /** @var string */
    protected $dir;

    /** @var string */
    protected $info;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__file_name() {
        return $this->file_name;
    }

    /** @return string */
    protected function __get__file_path() {
        return $this->file_path;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__dir() {
        return $this->dir;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return bool */
    protected function __get__valid() {
        return ($this->file_name && $this->name && $this->info) ? true : false;
    }

    //</editor-fold>

    public function __construct(string $dir, string $file_name, string $template_name) {
        $this->dir = \Helpers\Helpers::NEString(rtrim($dir, DIRECTORY_SEPARATOR), null);
        if ($this->dir) {
            $this->file_name = \Helpers\Helpers::NEString($file_name, null);
            $this->name = \Helpers\Helpers::NEString($template_name, null);
            if ($this->file_name && $this->name) {
                $this->file_path = $dir . DIRECTORY_SEPARATOR . $file_name;
                $info_path = $dir . DIRECTORY_SEPARATOR . $this->name . ".info";
                if (file_exists($info_path) && is_readable($info_path) && is_file($info_path)) {
                    $this->info = \Helpers\Helpers::NEString(strip_tags(\Helpers\Helpers::NEString(file_get_contents($info_path), '')), null);
                }
            }
        }
    }

    /**
     * 
     * @param string $dir
     * @param string $file_name
     * @param string $template_name
     * @return \Content\CatalogTile\CatalogTileTemplatesTemplateInfo
     */
    public static function F(string $dir, string $file_name, string $template_name): CatalogTileTemplatesTemplateInfo {
        return new static($dir, $file_name, $template_name);
    }

    protected function t_default_marshaller_export_property_file_path() {
        return "";
    }

    protected function t_default_marshaller_export_property_dir() {
        return "";
    }

}
