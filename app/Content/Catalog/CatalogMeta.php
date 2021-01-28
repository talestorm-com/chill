<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Catalog;

/**
 * @property int $catalog_id
 * @property string $meta_title
 * @property string $og_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $og_description
 * @property string $info
 */
class CatalogMeta implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="fields">
    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $catalog_id;

    /** @var string */
    protected $meta_title;

    /** @var string */
    protected $og_title;

    /** @var string */
    protected $meta_keywords;

    /** @var string */
    protected $meta_description;

    /** @var string */
    protected $og_description;

    /** @var string */
    protected $info;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__catalog_id() {
        return $this->catalog_id;
    }

    /** @return string */
    protected function __get__meta_title() {
        return $this->meta_title;
    }

    /** @return string */
    protected function __get__og_title() {
        return $this->og_title;
    }

    /** @return string */
    protected function __get__meta_keywords() {
        return $this->meta_keywords;
    }

    /** @return string */
    protected function __get__meta_description() {
        return $this->meta_description;
    }

    /** @return string */
    protected function __get__og_description() {
        return $this->og_description;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    //</editor-fold>
    //</editor-fold>



    public function __construct(int $catalog_id) {
        $this->catalog_id = $catalog_id;
        $this->load(); //спецкеш не нужен - оно работает вмнсте с кешируемым Content\Catalog
    }

    protected function load() {
        $query = "SELECT id,meta_title,og_title,meta_keywords,meta_description,og_description FROM catalog__group WHERE id=:P;";
        $data = \DB\DB::F()->queryRow($query, [":P" => $this->catalog_id]);
        $this->import_props(is_array($data) ? $data : []);
    }

    protected function t_common_import_get_filters() {
        return [
            "meta_title" => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            "og_title" => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            "meta_keywords" => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            "meta_description" => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            "og_description" => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            "info" => ["Trim", "NEString", "DefaultNull"], //string
        ];
    }

    /**
     * 
     * @param int $catalog_id
     * @return \Content\Catalog\CatalogMeta
     */
    public static function F(int $catalog_id): CatalogMeta {
        return new static($catalog_id);
    }

}
