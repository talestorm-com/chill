<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Catalog;

/**
 * @property int $catalog_id
 * @property int $page
 * @property int $perpage 
 * @property int $per_page
 * @property string $cache_key  
 * @property bool $dealer
 * @property int $offset
 * @note filters?
 */
class CatalogLoadParams implements \common_accessors\IMarshall, ICatalogLoadParams {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $catalog_id;

    /** @var int */
    protected $page;

    /** @var int */
    protected $perpage;

    /** @var bool */
    protected $dealer = false;

    /** @var string */
    protected $cache_key;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__catalog_id() {
        return $this->catalog_id;
    }

    /** @return int */
    protected function __get__page() {
        return $this->page;
    }

    /** @return int */
    protected function __get__perpage() {
        return $this->perpage;
    }
    /** @return int */
    protected function __get__per_page() {
        return $this->perpage;
    }

    /** @return string */
    protected function __get__cache_key() {
        return $this->cache_key;
    }

    /** @return bool */
    protected function __get__dealer() {
        return $this->dealer;
    }

    protected function __get__offset() {
        return $this->page * $this->perpage;
    }

    //</editor-fold>

    /**
     * builds param dependent string for cache key
     */
    protected function build_cache_key(): string {
        return md5(implode("|", [
            $this->catalog_id, $this->page, $this->perpage, $this->dealer ? "G" : "R"
        ]));
    }

    /**
     * 
     * @param int $catalog_id
     * @param int $page
     * @param int $perpage
     * @param string $sort_token
     */
    public function __construct(int $catalog_id, int $page = 0, int $perpage = 24) {
        $this->catalog_id = \Filters\FilterManager::F()->apply_chain($catalog_id, ['IntMore0', 'DefaultNull']);
        $this->page = \Filters\FilterManager::F()->apply_chain($page, ['IntMore0', 'Default0']);
        $this->perpage = \Filters\FilterManager::F()->apply_chain($perpage, ['IntMore0', 'Default0']);
        if (\Auth\Auth::F()->is_authentificated()) {
            if (\Auth\Auth::F()->get_user_info()->is_dealer) {
                if (\Auth\Auth::F()->is(\Auth\Roles\RoleDealer::class)) {
                    $this->dealer = true;
                }
            }
        }
        $this->cache_key = $this->build_cache_key();
    }

    /**
     * 
     * @param int $catalog_id
     * @param int $page
     * @param int $perpage
     * @return \Content\Catalog\CatalogLoadParams
     */
    public static function F(int $catalog_id, int $page = 0, int $perpage = 24): CatalogLoadParams {
        return new static($catalog_id, $page, $perpage);
    }

}
